<?php

namespace sys\entities\queries;

/**
 * This is the ActiveQuery class for [[\sys\entities\Language]].
 *
 * @see Language
 */
class LanguageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \sys\entities\Language[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \sys\entities\Language|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
