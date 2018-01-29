<?php

/**
 * BaseIntroFriendUnread
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $member_id
 * @property timestamp $read_at
 * @property integer $count
 * @property Member $Member
 * 
 * @method integer           getMemberId()  Returns the current record's "member_id" value
 * @method timestamp         getReadAt()    Returns the current record's "read_at" value
 * @method integer           getCount()     Returns the current record's "count" value
 * @method Member            getMember()    Returns the current record's "Member" value
 * @method IntroFriendUnread setMemberId()  Sets the current record's "member_id" value
 * @method IntroFriendUnread setReadAt()    Sets the current record's "read_at" value
 * @method IntroFriendUnread setCount()     Sets the current record's "count" value
 * @method IntroFriendUnread setMember()    Sets the current record's "Member" value
 * 
 * @package    OpenPNE
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseIntroFriendUnread extends opDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('intro_friend_unread');
        $this->hasColumn('member_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('read_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('count', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             'unsigned' => true,
             'length' => 4,
             ));

        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Member', array(
             'local' => 'member_id',
             'foreign' => 'id',
             'onDelete' => 'cascade',
             'owningSide' => true));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}