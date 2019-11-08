<?php

/**
 * ActivityData filter form.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kimura Youichi <yoichi.kimura@tejimaya.com>
 */
class ActivityDataFormFilter extends BaseActivityDataFormFilter
{
  public function configure()
  {
    $this->setWidget('id', new sfWidgetFormFilterInput(array('with_empty' => false)));
    $this->setValidator('id', new sfValidatorSchemaFilter('text', new sfValidatorDoctrineChoice(array(
      'model' => 'ActivityData',
      'column' => 'id',
      'required' => false,
    ))));

    $dateParams = array(
      'culture' => sfContext::getInstance()->getUser()->getCulture(),
      'month_format' => 'number',
      'can_be_empty' => true,
    );
    $this->setWidget('created_at', new sfWidgetFormFilterDate(array(
      'from_date' => new opWidgetFormDate($dateParams),
      'to_date' => new opWidgetFormDate($dateParams),
      'template' => '%from_date% 〜 %to_date%',
      'with_empty' => false,
    )));

    $this->setWidget('foreign_table', new sfWidgetFormFilterInput(array(
      'empty_label' => 'NULL',
      'template' => '%input% %empty_checkbox% %empty_label%',
    )));
    $this->setValidator('foreign_table', new sfValidatorSchemaFilter('text', new opValidatorString(array('required' => false))));

    $this->setWidget('foreign_id', new sfWidgetFormFilterInput(array('with_empty' => false)));

    $this->setWidget('in_reply_to_activity_id', new sfWidgetFormInput());

    $this->widgetSchema->setLabels(array(
      'id' => 'ID',
      'created_at' => 'Created Date',
      'member_id' => 'Author',
      'body' => 'Body',
      'foreign_table' => 'Foreign Table',
      'foreign_id' => 'Foreign ID',
      'in_reply_to_activity_id' => 'Reply To ID',
    ));

    $this->useFields(array('id', 'created_at', 'member_id', 'body', 'foreign_table', 'foreign_id', 'in_reply_to_activity_id'));
  }

  protected function addForeignTableColumnQuery(Doctrine_Query $query, $field, $values)
  {
    $fieldName = $this->getFieldName($field);

    if (isset($values['is_empty']) && $values['is_empty'])
    {
      $query->addWhere(sprintf('%s.%s IS NULL', $query->getRootAlias(), $fieldName));
    }
    elseif (isset($values['text']) && '' !== $values['text'])
    {
      $query->addWhere(sprintf('%s.%s = ?', $query->getRootAlias(), $fieldName), $values['text']);
    }
  }
}
