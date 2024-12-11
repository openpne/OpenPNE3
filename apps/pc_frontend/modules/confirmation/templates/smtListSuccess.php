<?php slot('op_sidemenu'); ?>
<?php
$categoryList = array();
foreach ($config as $k => $v)
{
  $categoryList[$k] = link_to(__($v), '@confirmation_list?category='.$k);
}

op_include_parts('pageNav', 'pageNav', array('list' => $categoryList, 'current' => $category));
?>
<?php end_slot(); ?>

<?php if (count($list)): ?>
  <div class="row">
    <div class="gadget_header span12"><?php echo __($config[$category]) ?></div>
  </div>
  <div class="row">
    <div class="pad12">
      <?php echo __('You have the following pending requests. Select "Accept" or "Reject".') ?>
    </div>

    <?php foreach ($list as $item): ?>
    <div class="pad12">
      <?php echo $form->renderFormTag(url_for('@confirmation_decision?id='.$item['id'].'&category='.$category)) ?>
      <?php echo $form->renderHiddenFields() ?>

      <table class="smt-table" style="width: 100%;">
        <?php foreach ($item['list'] as $k => $v): ?>
          <tr>
            <th style="width: 40%; "><?php echo __($k) ?></th>
            <td>
              <?php if ($k == '%nickname%'): ?>
                <div style="margin-bottom: 3px;">
                  <?php echo link_to(op_image_tag_sf_image($item['image']['url'], array('size' => '76x76', 'style' => 'width: 32px;')), $item['image']['link']); ?>
                </div>
              <?php endif; ?>
              <?php if (isset($v['link'])): ?>
                <?php echo link_to(nl2br($v['text']), $v['link']) ?>
              <?php else: ?>
                <?php echo nl2br($v['text']) ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr class="operation">
          <td colspan="3">
            <input type="submit" name="accept" value="<?php echo __('Accept') ?>" class="input_submit" />
            <input type="submit" value="<?php echo __('Reject') ?>" class="input_submit" />
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
      </form>
    </div>
  </div>

<?php else: ?>
  <div class="row">
    <div class="gadget_header span12"><?php echo __($config[$category]) ?></div>
  </div>
  <div class="row">
    <div class="pad12">
      <?php echo __('You don\'t have any pending requests') ?>
    </div>
  </div>
<?php endif; ?>
