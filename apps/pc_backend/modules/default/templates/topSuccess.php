<div id="versionInformation" style="display: none;"></div>
<script type="text/javascript">
function getVersion(obj)
{
  if (obj)
  {
    var info = document.getElementById('versionInformation');
    new Insertion.Top(info, '<p class="'+obj.level+'">'+obj.message+'</p>');
    info.show();
  }
}
</script>
<script type="text/javascript" src="http://sandbox.ebihara.dazai.pne.jp/OpenPNE3Develop/version.php?callback=getVersion&version=<?php echo OPENPNE_VERSION ?>&url=<?php echo urlencode($sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot().'/') ?>"></script>

メニューから項目を選択してください。
