<?php

namespace sys\entities\queries;

/**
 * This is the ActiveQuery class for [[\sys\entities\Domain]].
 *
 * @see \sys\entities\Domain
 */
class DomainQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \sys\entities\Domain[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \sys\entities\Domain|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
