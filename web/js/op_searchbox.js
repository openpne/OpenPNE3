/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 *
 * @license Apache-2.0
 * @author  Kimura Youichi <kim.upsilon@bucyou.net>
 */
var opSearchBox = (function () {
    function opSearchBox(baseElm, listItemTmpl, endpoint, params) {
        this.curSearchText = '';
        this.loading = 0; // 1 以上の時は読み込み中アイコンを表示し続ける
        this.searchBox = $('.searchBox', baseElm);
        this.loadingImage = $('.loadingImage', baseElm);
        this.resultList = $('.resultList', baseElm);
        this.listItemTemplate = listItemTmpl;

        this.searchEndpoint = endpoint;
        this.searchParams = params || {};

        this.initEventHandler();
    }

    opSearchBox.prototype.initEventHandler = function () {
        var _this = this;
        this.searchBox.keyup(function () {
            var searchText = _this.searchBox.val();
            if (searchText !== _this.curSearchText) {
                _this.curSearchText = searchText;
                _this.resultList.empty();
                _this.search(searchText);
            }
        });
    };

    opSearchBox.prototype.fetchSearchResults = function (params) {
        params = $.extend({}, this.searchParams, params);
        params['apiKey'] = openpne.apiKey;

        return $.getJSON(openpne.apiBase + this.searchEndpoint, params);
    };

    opSearchBox.prototype.search = function (keyword) {
        var _this = this;

        /*
         * +-----------------+
         * | +-------------+ |
         * | | .resultBox  | | <= first load
         * | +-------------+ |
         * | +-------------+ |
         * | | .resultBox  | | <= load more
         * | +-------------+ |
         * +-----------------+
         *     .resultList
         */
        var resultBox = $('<div/>', { 'class': 'resultBox row' });
        this.resultList.append(resultBox);

        this.loadingImage.show();
        this.loading++;

        var params = {};

        if (keyword !== undefined && keyword !== '')
            params['keyword'] = keyword;

        this.fetchSearchResults(params)
            .then(function (json) {
                var results = json.data;
                if (results.length === 0)
                    return;

                _this.listItemTemplate.tmpl(results)
                    .appendTo(resultBox);
            })
            .always(function () {
                if (--_this.loading < 1)
                    _this.loadingImage.hide();
            });
    };

    return opSearchBox;
})();
