<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\commands;

use Yii;
use sys\entities\User;
use yii\helpers\Console;
use yii\console\Controller;
use sys\helpers\ArrayHelper;
use sys\repositories\user\UserRepository;

/**
 * Interactive console user manager.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * UserController constructor.
     * @param string $id
     * @param \yii\base\Module $module
     * @param UserRepository $repository
     * @param array $config
     */
   public function __construct($id, $module, UserRepository $repository, array $config = [])
   {
       parent::__construct($id, $module, $config);
       $this->repository = $repository;

   }

    /**
     * Creates new user.
     */
    public function actionCreate()
    {
        $user = new User();
        $this->readValue($user, 'username');
        $this->readValue($user, 'email');
        $user->setPassword($this->prompt('Password:', [
            'required' => true,
            'pattern'  => '#^.{6,255}$#i',
            'error'    => 'More than 6 symbols',
        ]));
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $this->log($this->repository->save($user));
    }

    /**
     * Removes user by username.
     */
    public function actionRemove()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $this->log($this->repository->remove($user));
    }

    /**
     * Activates user.
     */
    public function actionActivate()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $user->status = User::STATUS_ACTIVE;
        $this->log($this->repository->save($user));
    }

    /**
     * Blocked user.
     */
    public function actionBlocked()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $user->status = User::STATUS_BLOCKED;
        $this->log($this->repository->save($user));
    }

    /**
     * Changes user password.
     */
    public function actionChangePassword()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $user->setPassword($this->prompt('New password:', [
            'required' => true,
            'pattern' => '#^.{6,255}$#i',
            'error' => 'More than 6 symbols',
        ]));
        $this->log($this->repository->save($user));
    }

    /**
     * Adds role to user.
     */
    public function actionRoleAssign()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $authManager = Yii::$app->getAuthManager();
        $roleName = $this->select('Role:', ArrayHelper::map($authManager->getRoles(), 'name', 'description'));
        $role = $authManager->getRole($roleName);
        $authManager->assign($role, $user->id);
        $this->log(true);
    }

    /**
     * Removes role from user.
     */
    public function actionRoleRevoke() : void
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $authManager = Yii::$app->getAuthManager();
        $roleName = $this->select('Role:', ArrayHelper::merge(
            ['all' => 'All Roles'],
            ArrayHelper::map($authManager->getRolesByUser($user->id), 'name', 'description'))
        );
        if ($roleName == 'all') {
            $authManager->revokeAll($user->id);
        } else {
            $role = $authManager->getRole($roleName);
            $authManager->revoke($role, $user->id);
        }
        $this->log(true);
    }

    /**
     * Generates default roles.
     * @return void
     */
    public function actionRbacInit() : void
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        $backend = $auth->createPermission('backend');
        $backend->description = 'Backend panel Permission';
        $auth->add($backend);

        $assetsForcedCopy = $auth->createPermission('assetsForcedCopy');
        $assetsForcedCopy->description = 'Assets Forced Copy Permission';
        $auth->add($assetsForcedCopy);

        $notDenyAccess = $auth->createPermission('notDenyAccess');
        $notDenyAccess->description = 'notDenyAccess Permission';
        $auth->add($notDenyAccess);

        $user = $auth->createRole('user');
        $user->description = 'User Role';
        $auth->add($user);

        $admin = $auth->createRole('administrator');
        $admin->description = 'Administrator Role';
        $auth->add($admin);

        $auth->addChild($admin, $user);
        $auth->addChild($admin, $backend);
        $auth->addChild($admin, $notDenyAccess);
        $auth->addChild($admin, $assetsForcedCopy);

        $this->log(true);
    }

    /**
     * Find user.
     * @param string $username
     * @throws \yii\console\Exception
     * @return User the User loaded model
     */
    private function findModel(string $username) : User
    {
        return $this->repository->getByUsernameOrEmail($username);
    }

    /**
     * Get the value of the console.
     * @param User   $user
     * @param string $attribute
     * @return void
     */
    private function readValue(User $user, string $attribute) : void
    {
        $user->$attribute = $this->prompt(mb_convert_case($attribute, MB_CASE_TITLE, 'utf-8') . ':', [
            'validator' => function ($input, &$error) use ($user, $attribute) {
                $user->$attribute = $input;
                if ($user->validate([$attribute])) {
                    return true;
                } else {
                    $error = implode(',', $user->getErrors($attribute));
                    return false;
                }
            },
        ]);
    }

    /**
     * Console log.
     * @param bool $success
     * @return void
     */
    private function log(bool $success) : void
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        $this->stdout(PHP_EOL);
    }
}