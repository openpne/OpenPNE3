INSERT INTO member_config (member_id, name, value) (SELECT c_member_id, "password", hashed_password FROM c_member_secure);
INSERT INTO member_config (member_id, name, value) (SELECT c_member_id, "mobile_uid", easy_access_id FROM c_member_secure);

<?php
$questions = array(
  '1' => '母または父の旧姓は?',
  '2' => '運転免許証番号の下 5 桁は?',
  '3' => '初恋の人の名前は?',
  '4' => '卒業した小学校の名前は？',
  '5' => '本籍地の県名は？',
);
?>

<?php foreach ($questions as $id => $question): ?>
INSERT INTO member_config (member_id, name, value) (SELECT c_member_id, "secret_question", <?php echo $id ?>  FROM c_member_secure WHERE c_member_id IN (SELECT c_member_id FROM c_member WHERE c_password_query_id = (SELECT c_password_query_id FROM c_password_query WHERE c_password_query_question = "<?php echo $question ?>")));
<?php endforeach; ?>
INSERT INTO member_config (member_id, name, value) (SELECT c_member_id, "secret_answer", hashed_password_query_answer FROM c_member_secure WHERE c_member_id IN (SELECT member_id FROM member_config WHERE name = "secret_question"));
