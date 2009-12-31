INSERT INTO member_config (member_id, name, value, name_value_hash) (SELECT c_member_id, "password", hashed_password, MD5(<?php echo $this->conn->expression->concat($this->conn->quote('password'), $this->conn->quote(','), 'hashed_password') ?>) FROM c_member_secure);
INSERT INTO member_config (member_id, name, value, name_value_hash) (SELECT c_member_id, "mobile_uid", easy_access_id, MD5(<?php echo $this->conn->expression->concat($this->conn->quote('mobile_uid'), $this->conn->quote(','), 'easy_access_id') ?>) FROM c_member_secure);

<?php
$questions = array(
  '母または父の旧姓は?' => '1',
  '母または父の旧姓は？' => '1',
  '運転免許証番号の下 5 桁は?' => '2',
  '運転免許証番号の下 5 桁は？' => '2',
  '初恋の人の名前は?' => '3',
  '初恋の人の名前は？' => '3',
  '卒業した小学校の名前は?' => '4',
  '卒業した小学校の名前は？' => '4',
  '本籍地の県名は?' => '5',
  '本籍地の県名は？' => '5',
);
?>

<?php foreach ($questions as $question => $id): ?>
INSERT INTO member_config (member_id, name, value, name_value_hash) (SELECT c_member_id, "secret_question", <?php echo $id ?>, MD5(<?php echo $this->conn->expression->concat($this->conn->quote('secret_question'), $this->conn->quote(','), $id) ?>) FROM c_member_secure WHERE c_member_id IN (SELECT c_member_id FROM c_member WHERE c_password_query_id = (SELECT c_password_query_id FROM c_password_query WHERE c_password_query_question = "<?php echo $question ?>")) AND c_member_id NOT IN (SELECT member_id FROM member_config WHERE name = "secret_question"));
<?php endforeach; ?>
INSERT INTO member_config (member_id, name, value, name_value_hash) (SELECT c_member_id, "secret_answer", hashed_password_query_answer, MD5(<?php echo $this->conn->expression->concat($this->conn->quote('secret_answer'), $this->conn->quote(','), 'hashed_password_query_answer') ?>) FROM c_member_secure WHERE c_member_id IN (SELECT member_id FROM member_config WHERE name = "secret_question"));
