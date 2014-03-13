function testErrorCode(jqXHR, errorInfo) {
  var response = JSON.parse(jqXHR.responseText);
  var responseErrorData = response.error;

  var errorCode = responseErrorData.code;
  var errorMessage = responseErrorData.message;

  equal(errorCode, errorInfo.code, 'エラーコードのチェック');
  equal(errorMessage, errorInfo.message, 'エラーメッセージ');
}

function getResponseDataByjqXHR(jqXHR) {
  var response = JSON.parse(jqXHR.responseText);
  return response.data;
}

function runTests(apiBase, apiKeys) {
  var apiUrl = apiBase + 'timeline/search.json?apiKey=' + apiKeys[1];

  QUnit.moduleStart(function(details) {
    //  $.ajax(apiBase + 'test/setup.json?force=1', { async: false });
    });

  module('検索動作テスト');

  asyncTest('公開範囲を取得する', 1, function() {
    $.getJSON(apiUrl)
    .complete(function(jqXHR){

      var responseData = getResponseDataByjqXHR(jqXHR);

      var publicFlags = [];
      for (var i = 0; i < responseData.length; i++)
      {
        publicFlags.push({
          body: responseData[i].body,
          public_status: responseData[i].public_status
        });
      }

      var expected = [
      {
        body: '公開しない',
        public_status: 'private'
      },

      {
        body: 'マイフレンドに公開',
        public_status: 'friend'
      },

      {
        body: '全員に公開',
        public_status: 'sns'
      },
      ];

      deepEqual(publicFlags, expected);
      start();
    });
  });

  module('不正パラメーターチェック');

  asyncTest('ターゲットの指定が不正だったら400', 2, function() {
    $.getJSON(apiUrl + '&target=error')
    .complete(function(jqXHR){

      testErrorCode(jqXHR, {
        code: 400,
        message: 'target parameter is invalid.'
      });
        
      start();
    });
  });

  asyncTest('ターゲットがコミュニティだがターゲットIDの指定をしていなかったら400', 2, function() {
    $.getJSON(apiUrl + '&target=community')
    .complete(function(jqXHR){

      testErrorCode(jqXHR, {
        code: 400,
        message: 'target_id parameter not specified.'
      });

      start();
    });
  });

}

runTests(
  '../../api_test.php/',
  {
    '1': '1ea796288ec18cd37e8feccdbf01f62df5a3bd453248c039b6507a46d2f9bc60'
  }
);
