<?php
/**
* openpne:delete-expired-invitation task deletes invited members who have not joined the SNS.
*
* @auther Hiromi Hishida <info@77-web.com>
*/
class openpneDeleteExpiredInvitationTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace = 'openpne';
    $this->name = 'delete-expired-invitation';
    
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment', 'dev'),
    ));

    $this->briefDescription = 'delete expired invitations';
    $this->detailedDescription = <<<EOF
The [openpne:delete-expired-invitation|INFO] task deletes invited members' who have not actually joined the SNS.
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $days = (int)opConfig::get('invitation_expire_days', 30);
    $limitDate = date('Y-m-d H:i:s', time() - 60*60*24*$days);
    
    opActivateBehavior::disable();
    $q = Doctrine_Query::create()
           ->delete()
           ->from('Member m')
           ->where('m.updated_at <= ?', $limitDate)
           ->addWhere('m.id in (SELECT mc.member_id FROM MemberConfig mc WHERE mc.name = ?)', 'register_token');
    
    $q->execute();
  }
}