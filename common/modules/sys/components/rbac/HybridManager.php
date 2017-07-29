<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\rbac;

use Yii;
use yii\db\ActiveRecord;
use yii\rbac\Assignment;
use yii\rbac\PhpManager;
use yii\base\InvalidParamException;
use sys\exceptions\NotFoundException;
use sys\interfaces\HybridManagerInterface;

/**
 * Class HybridManager.
 */
class HybridManager extends PhpManager
{

    /**
     * @var HybridManagerInterface
     */
    public $model = 'sys\entities\User';

    /**
     * @inheritdoc
     */
    public function getAssignments($id)
    {
        $assignments = [];
        if ($id && $user = $this->getUser($id)) {
            foreach ($user->getAuthRoleNames() as $roleName) {
                $assignment = new Assignment();
                $assignment->userId = $id;
                $assignment->roleName = $roleName;
                $assignments[$assignment->roleName] = $assignment;
            }
        }
        return $assignments;

    }

    /**
     * @inheritdoc
     */
    public function getAssignment($role, $id)
    {
        if ($id && $user = $this->getUser($id)) {
            if (in_array($role, $user->getAuthRoleNames())) {
                $assignment = new Assignment();
                $assignment->userId = $id;
                $assignment->roleName = $role;

                return $assignment;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUserIdsByRole($role)
    {
        return $this->model::findAuthIdsByRoleName($role);
    }

    /**
     * @inheritdoc
     */
    protected function loadFromFile($file) : array
    {
        if ($this->assignmentFile == $file) {
          return [];
        }
        return parent::loadFromFile($file);
    }

    /**
     * @inheritdoc
     */
    public function assign($role, $id)
    {
        if ($id && $user = $this->getUser($id)) {
            if (in_array($role->name, $user->getAuthRoleNames())) {
                throw new InvalidParamException(
                    Yii::t(
                        'sys/user',
                        'Authorization item [{role}] has already been assigned to user [{id}]',
                        [
                            'role' => $role->name,
                            'id'   => $id
                        ]
                    )
                );
            } else {
                $assignment = new Assignment([
                    'userId' => $id,
                    'roleName' => $role->name,
                    'createdAt' => time(),
                ]);
                $user->addAuthRoleName($role->name);
                return $assignment;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function revoke($role, $id)
    {
        if ($id && $user = $this->getUser($id)) {
            if (in_array($role->name, $user->getAuthRoleNames())) {
                $user->removeAuthRoleName($role->name);
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function revokeAll($id)
    {
        if ($id && $user = $this->getUser($id)) {
            $user->clearAuthRoleNames();
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
   public function saveToFile($data, $file) : void
   {
       if ($this->assignmentFile == $file) {
           return;
       }
       parent::saveToFile($data, $file);
   }

    /**
     * @param $id
     * @return array|ActiveRecord
     */
    public function getUser($id) : ActiveRecord
    {
        $user = Yii::$app->get('user', false);
        if ($user && !$user->getIsGuest() && $user->getId() == $id) {
            return $user->getIdentity()->getUser();
        }
        /* @var $user ActiveRecord */
        if (!$user = $this->model::findOne($id)) {
            throw new NotFoundException(Yii::t('sys/user', 'User not found'));
        }
        return $user;
    }
}