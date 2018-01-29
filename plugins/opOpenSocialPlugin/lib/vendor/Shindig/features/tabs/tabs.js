/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/**
 * @fileoverview Tabs library for gadgets.
 */

var gadgets = gadgets || {};

/**
 * @class Tab class for gadgets.
 * You create tabs using the TabSet addTab() method.
 * To get Tab objects,
 * use the TabSet getSelectedTab() or getTabs() methods.
 *
 * <p>
 * <b>See also:</b>
 * <a href="gadgets.TabSet.html">TabSet</a>
 * </p>
 *
 * @name gadgets.Tab
 * @description Creates a new Tab.
 */

/**
 * @param {gadgets.TabSet} handle The associated gadgets.TabSet instance.
 * @private
 * @constructor
 */
gadgets.Tab = function(handle) {
  this.handle_ = handle;
  this.td_ = null;
  this.contentContainer_ = null;
  this.callback_ = null;
};

/**
 * Returns the label of the tab as a string (may contain HTML).
 * @return {String} Label of the tab.
 */
gadgets.Tab.prototype.getName = function() {
  return this.td_.innerHTML;
};

/**
 * Returns the HTML element that contains the tab's label.
 * @return {Element} The HTML element of the tab's label.
 */
gadgets.Tab.prototype.getNameContainer = function() {
  return this.td_;
};

/**
 * Returns the HTML element where the tab content is rendered.
 * @return {Element} The HTML element of the content container.
 */
gadgets.Tab.prototype.getContentContainer = function() {
  return this.contentContainer_;
};

/**
 * Returns the callback function that is executed when the tab is selected.
 * @return {Function} The callback function of the tab.
 */
gadgets.Tab.prototype.getCallback = function() {
  return this.callback_;
};

/**
 * Returns the tab's index.
 * @return {Number} The tab's index.
 */
gadgets.Tab.prototype.getIndex = function() {
  var tabs = this.handle_.getTabs();
  for (var i = 0; i < tabs.length; ++i) {
    if (this === tabs[i]) {
      return i;
    }
  }
  return -1;
};

/**
 * @class A class gadgets can use to make tabs.
 * @description Creates a new TabSet object
 *
 * @param {String} opt_moduleId Optional suffix for the ID of tab container.
 * @param {String} opt_defaultTab Optional tab name that specifies the name of
 *                   of the tab that is selected after initialization.
 *                   If this parameter is omitted, the first tab is selected by
 *                   default.
 * @param {Element} opt_container The HTML element to contain the tabs.  If
 *                    omitted, a new div element is created and inserted at the
 *                    very top.
 */
gadgets.TabSet = function(opt_moduleId, opt_defaultTab, opt_container) {
  this.moduleId_ = opt_moduleId || 0;
  this.domIdFilter_ = new RegExp('^[A-Za-z]([0-9a-zA-Z_:.-]+)?$');
  this.selectedTab_ = null;
  this.tabs_ = [];
  this.tabsAdded_ = 0;
  this.defaultTabName_ = opt_defaultTab || '';
  this.leftNavContainer_ = null;
  this.rightNavContainer_ = null;
  this.navTable_ = null;
  this.tabsContainer_ = null;
  this.rtl_ = document.body.dir === 'rtl';
  this.mainContainer_ = this.createMainContainer_(opt_container);
  this.tabTable_ = this.createTabTable_();
  this.displayTabs(false);
  gadgets.TabSet.addCSS_([
    '.tablib_table {',
      'width: 100%;',
      'border-collapse: separate;',
      'border-spacing: 0px;',
      'empty-cells: show;',
      'font-size: 11px;',
      'text-align: center;',
    '}',
    '.tablib_emptyTab {',
      'border-bottom: 1px solid #676767;',
      'padding: 0px 1px;',
    '}',
    '.tablib_spacerTab {',
      'border-bottom: 1px solid #676767;',
      'padding: 0px 1px;',
      'width: 1px;',
    '}',
    '.tablib_selected {',
      'padding: 2px;',
      'background-color: #ffffff;',
      'border: 1px solid #676767;',
      'border-bottom-width: 0px;',
      'color: #3366cc;',
      'font-weight: bold;',
      'width: 80px;',
      'cursor: default;',
    '}',
    '.tablib_unselected {',
      'padding: 2px;',
      'background-color: #dddddd;',
      'border: 1px solid #aaaaaa;',
      'border-bottom-color: #676767;',
      'color: #000000;',
      'width: 80px;',
      'cursor: pointer;',
    '}',
    '.tablib_navContainer {',
      'width: 10px;',
      'vertical-align: middle;',
    '}',
    '.tablib_navContainer a:link, ',
    '.tablib_navContainer a:visited, ',
    '.tablib_navContainer a:hover {',
      'color: #3366aa;',
      'text-decoration: none;',
    '}'
  ].join(''));
};

/**
 * Adds a new tab based on the name-value pairs specified in opt_params.
 * @param {String} tabName Label of the tab to create.
 * @param {Object} opt_params Optional parameter object. The following
 *                   properties are supported:
 *                   .contentContainer An existing HTML element to be used as
 *                     the tab content container. If omitted, the tabs
 *                     library creates one.
 *                   .callback A callback function to be executed when the tab
 *                     is selected.
 *                   .tooltip A tooltip description that pops up when user moves
 *                     the mouse cursor over the tab.
 *                   .index The index at which to insert the tab. If omitted,
 *                     the new tab is appended to the end.
 * @return {String} DOM id of the tab container.
 */
gadgets.TabSet.prototype.addTab = function(tabName, opt_params) {
  if (typeof opt_params === 'string') {
    opt_params = {contentContainer: document.getElementById(arguments[1]),
                  callback: arguments[2]};
  }

  var params = opt_params || {};

  var tabIndex = -1;
  if (params.index >= 0 && params.index < this.tabs_.length) {
    tabIndex = params.index;
  }
  var tab = this.createTab_(tabName, {
      contentContainer: params.contentContainer,
      callback: params.callback,
      tooltip: params.tooltip
  });

  var tr = this.tabTable_.rows[0];
  if (this.tabs_.length > 0) {
    var filler = document.createElement('td');
    filler.className = this.cascade_('tablib_spacerTab');
    filler.appendChild(document.createTextNode(' '));

    var ref = tabIndex < 0 ? tr.cells[tr.cells.length - 1] : this.tabs_[tabIndex].td_;
    tr.insertBefore(filler, ref);
    tr.insertBefore(tab.td_, tabIndex < 0 ? ref : filler);
  } else {
    tr.insertBefore(tab.td_, tr.cells[tr.cells.length - 1]);
  }

  if (tabIndex < 0) {
    tabIndex = this.tabs_.length;
    this.tabs_.push(tab);
  } else {
    this.tabs_.splice(tabIndex, 0, tab);
  }

  if (tabName == this.defaultTabName_ || (!this.defaultTabName_ && tabIndex === 0)) {
    this.selectTab_(tab);
  }

  this.tabsAdded_++;
  this.displayTabs(true);
  this.adjustNavigation_();

  return tab.contentContainer_.id;
};

/**
 * Removes a tab at tabIndex and all of its associated content.
 * @param {Number} tabIndex Index of the tab to remove.
 */
gadgets.TabSet.prototype.removeTab = function(tabIndex) {
  var tab = this.tabs_[tabIndex];
  if (tab) {
    if (tab === this.selectedTab_) {
      var maxIndex = this.tabs_.length - 1;
      if (maxIndex > 0) {
        this.selectTab_(tabIndex < maxIndex ?
          this.tabs_[tabIndex + 1] :
          this.tabs_[tabIndex - 1]);
      }
    }
    var tr = this.tabTable_.rows[0];
    if (this.tabs_.length > 1) {
      tr.removeChild(tabIndex ? tab.td_.previousSibling : tab.td_.nextSibling);
    }
    tr.removeChild(tab.td_);
    this.mainContainer_.removeChild(tab.contentContainer_);
    this.tabs_.splice(tabIndex, 1);
    this.adjustNavigation_();
    if (this.tabs_.length === 0) {
      this.displayTabs(false);
      this.selectedTab_ = null;
    }
  }
};

/**
 * Returns the currently selected tab object.
 * @return {gadgets.Tab} The currently selected tab object.
 */
gadgets.TabSet.prototype.getSelectedTab = function() {
  return this.selectedTab_;
};

/**
 * Selects the tab at tabIndex and fires the tab's callback function if it
 * exists. If the tab is already selected, the callback is not fired.
 * @param {Number} tabIndex Index of the tab to select.
 */
gadgets.TabSet.prototype.setSelectedTab = function(tabIndex) {
  if (this.tabs_[tabIndex]) {
    this.selectTab_(this.tabs_[tabIndex]);
  }
};

/**
 * Swaps the positions of tabs at tabIndex1 and tabIndex2. The selected tab
 * does not change, and no callback functions are called.
 * @param {Number} tabIndex1 Index of the first tab to swap.
 * @param {Number} tabIndex2 Index of the secnod tab to swap.
 */
gadgets.TabSet.prototype.swapTabs = function(tabIndex1, tabIndex2) {
  var tab1 = this.tabs_[tabIndex1];
  var tab2 = this.tabs_[tabIndex2];
  if (tab1 && tab2) {
    var tr = tab1.td_.parentNode;
    var slot = tab1.td_.nextSibling;
    tr.insertBefore(tab1.td_, tab2.td_);
    tr.insertBefore(tab2.td_, slot);
    this.tabs_[tabIndex1] = tab2;
    this.tabs_[tabIndex2] = tab1;
  }
};


/**
 * Returns an array of all existing tab objects.
 * @return {Array.&lt;gadgets.Tab&gt;} Array of all existing tab objects.
 */
gadgets.TabSet.prototype.getTabs = function() {
  return this.tabs_;
};

/**
 * Sets the alignment of tabs. Tabs are center-aligned by default.
 * @param {String} align 'left', 'center', or 'right'.
 * @param {Number} opt_offset Optional parameter to set the number of pixels
 *                   to offset tabs from the left or right edge. The default
 *                   value is 3px.
 */
gadgets.TabSet.prototype.alignTabs = function(align, opt_offset) {
  var tr = this.tabTable_.rows[0];
  var left = tr.cells[0];
  var right = tr.cells[tr.cells.length - 1];
  var offset = isNaN(opt_offset) ? '3px' : opt_offset + 'px';
  left.style.width = align === 'left' ? offset : '';
  right.style.width = align === 'right' ? offset : '';
  // In Opera and potentially some other browsers, changes to the width of
  // table cells aren't rendered.  To fix this, we force to re-render the
  // table by hiding and showing it again.
  this.tabTable_.style.display = 'none';
  this.tabTable_.style.display = '';
};

/**
 * Shows or hides tabs and all associated content.
 * @param {Boolean} display true to show tabs; false to hide tabs.
 */
gadgets.TabSet.prototype.displayTabs = function(display) {
  this.mainContainer_.style.display = display ? 'block' : 'none';
};

/**
 * Returns the tab headers container element.
 * @return {Element} The tab headers container element.
 */
gadgets.TabSet.prototype.getHeaderContainer = function() {
  return this.tabTable_;
};

/**
 * Helper method that returns an HTML container element to which all tab-related
 * content will be appended.
 * This container element is created and inserted as the first child of the
 * gadget if opt_element is not specified.
 * @param {Element} opt_element Optional HTML container element.
 * @return {Element} HTML container element.
 */
gadgets.TabSet.prototype.createMainContainer_ = function(opt_element) {
  var newId = 'tl_' + this.moduleId_;
  var container = opt_element || document.getElementById(newId);

  if (!container) {
    container = document.createElement('div');
    container.id = newId;
    document.body.insertBefore(container, document.body.firstChild);
  }

  container.className = this.cascade_("tablib_main_container") + ' ' +
    container.className;

  return container;
};

/**
 * Helper method that expands a class name into two class names.
 * @param {String} label CSS class
 * @return {String} Expanded class names.
 */
gadgets.TabSet.prototype.cascade_ = function(label) {
  return label + ' ' + label + this.moduleId_;
};

/**
 * Helper method that creates the tabs table and inserts it into the main
 * container as the first child.
 * @return {Element} HTML element of the tab container table.
 */
gadgets.TabSet.prototype.createTabTable_ = function() {
  var table = document.createElement('table');
  table.id = this.mainContainer_.id + '_header';
  table.className = this.cascade_('tablib_table');
  table.cellSpacing = '0';
  table.cellPadding = '0';

  var tbody = document.createElement('tbody');
  var tr = document.createElement('tr');
  tbody.appendChild(tr);
  table.appendChild(tbody);

  var emptyTd = document.createElement('td');
  emptyTd.className = this.cascade_('tablib_emptyTab');
  emptyTd.appendChild(document.createTextNode(' '));
  tr.appendChild(emptyTd);
  tr.appendChild(emptyTd.cloneNode(true));

  // Construct a wrapper table around our tab table to house the navigation
  // elements. These elements will appear if the tab table overflows.
  var navTable = document.createElement('table');
  navTable.id = this.mainContainer_.id + '_navTable';
  navTable.style.width = '100%';
  navTable.cellSpacing = '0';
  navTable.cellPadding = '0';
  navTable.style.tableLayout = 'fixed';
  var navTbody = document.createElement('tbody');
  var navTr = document.createElement('tr');
  navTbody.appendChild(navTr);
  navTable.appendChild(navTbody);

  // Create the left navigation element.
  var leftNavTd = document.createElement('td');
  leftNavTd.className = this.cascade_('tablib_emptyTab') + ' ' +
                        this.cascade_('tablib_navContainer');
  leftNavTd.style.textAlign = 'left';
  leftNavTd.style.display = '';
  var leftNav = document.createElement('a');
  leftNav.href = 'javascript:void(0)';
  leftNav.innerHTML = '&laquo;';
  leftNavTd.appendChild(leftNav);
  navTr.appendChild(leftNavTd);

  // House the actual tab table in the middle, hiding any overflow.
  var tabNavTd = document.createElement('td');
  navTr.appendChild(tabNavTd);
  var wrapper = document.createElement('div');
  wrapper.style.width = '100%';
  wrapper.style.overflow = 'hidden';
  wrapper.appendChild(table);
  tabNavTd.appendChild(wrapper);

  // Create the right navigation element.
  var rightNavTd = document.createElement('td');
  rightNavTd.className = this.cascade_('tablib_emptyTab') + ' ' +
                         this.cascade_('tablib_navContainer');
  rightNavTd.style.textAlign = 'right';
  rightNavTd.style.display = '';
  var rightNav = document.createElement('a');
  rightNav.href = 'javascript:void(0)';
  rightNav.innerHTML = '&raquo;';
  rightNavTd.appendChild(rightNav);
  navTr.appendChild(rightNavTd);

  // Register onclick event handlers for smooth scrolling.
  var me = this;
  leftNav.onclick = function(event) {
    me.smoothScroll_(wrapper, -120);
  };
  rightNav.onclick = function(event) {
    me.smoothScroll_(wrapper, 120);
  };

  // Swap left and right scrolling if direction is RTL.
  if (this.rtl_) {
    var temp = leftNav.onclick;
    leftNav.onclick = rightNav.onclick;
    rightNav.onclick = temp;
  }

  // If we're already displaying tabs, then remove them.
  if (this.navTable_) {
    this.mainContainer_.replaceChild(navTable, this.navTable_);
  } else {
    this.mainContainer_.insertBefore(navTable, this.mainContainer_.firstChild);
    var adjustNavigationFn = function() {
      me.adjustNavigation_();
    };
    if (window.addEventListener) {
      window.addEventListener('resize', adjustNavigationFn, false);
    } else if (window.attachEvent) {
      window.attachEvent('onresize', adjustNavigationFn);
    }
  }

  this.navTable_ = navTable;
  this.leftNavContainer_ = leftNavTd;
  this.rightNavContainer_ = rightNavTd;
  this.tabsContainer_ = wrapper;

  return table;
};

/**
 * Helper method that shows or hides the navigation elements.
 */
gadgets.TabSet.prototype.adjustNavigation_ = function() {
  this.leftNavContainer_.style.display = 'none';
  this.rightNavContainer_.style.display = 'none';
  if (this.tabsContainer_.scrollWidth <= this.tabsContainer_.offsetWidth) {
    if(this.tabsContainer_.scrollLeft) {
      // to avoid JS error in IE
      this.tabsContainer_.scrollLeft = 0;
    }
    return;
  }

  this.leftNavContainer_.style.display = '';
  this.rightNavContainer_.style.display = '';
  if (this.tabsContainer_.scrollLeft + this.tabsContainer_.offsetWidth >
      this.tabsContainer_.scrollWidth) {
    this.tabsContainer_.scrollLeft = this.tabsContainer_.scrollWidth -
                                     this.tabsContainer_.offsetWidth;
  } else if (this.rtl_) {
    this.tabsContainer_.scrollLeft = this.tabsContainer_.scrollWidth;
  }
};

/**
 * Helper method that smoothly scrolls the tabs container.
 * @param {Element} container The tabs container element.
 * @param {Number} distance The amount of pixels to scroll right.
 */
gadgets.TabSet.prototype.smoothScroll_ = function(container, distance) {
  var scrollAmount = 10;
  if (!distance) {
    return;
  } else {
    container.scrollLeft += (distance < 0) ? -scrollAmount : scrollAmount;
  }

  var nextScroll = Math.min(scrollAmount, Math.abs(distance));
  var me = this;
  var timeoutFn = function() {
    me.smoothScroll_(container, (distance < 0) ? distance + nextScroll :
                                                 distance - nextScroll);
  };
  setTimeout(timeoutFn, 10);
};

/**
 * Helper function that dynamically inserts CSS rules to the page.
 * @param {String} cssText CSS rules to inject
 * @private
 */
gadgets.TabSet.addCSS_ = function(cssText) {
  var head = document.getElementsByTagName('head')[0];
  if (head) {
    var styleElement = document.createElement('style');
    styleElement.type = 'text/css';
    if (styleElement.styleSheet) {
      styleElement.styleSheet.cssText = cssText;
    } else {
      styleElement.appendChild(document.createTextNode(cssText));
    }
    head.insertBefore(styleElement, head.firstChild);
  }
};

/**
 * Helper method that creates a new gadgets.Tab object.
 * @param {String} tabName Label of the tab to create.
 * @param {Object} params Parameter object. The following properties
 *                   are supported:
 *                   .contentContainer An existing HTML element to be used as
 *                     the tab content container. If omitted, the tabs
 *                     library creates one.
 *                   .callback A callback function to be executed when the tab
 *                     is selected.
 *                   .tooltip A tooltip description that pops up when user moves
 *                     the mouse cursor over the tab.
 * @return {gadgets.Tab} A new gadgets.Tab object.
 */
gadgets.TabSet.prototype.createTab_ = function(tabName, params) {
  var tab = new gadgets.Tab(this);
  tab.contentContainer_ = params.contentContainer;
  tab.callback_ = params.callback;
  tab.td_ = document.createElement('td');
  tab.td_.title = params.tooltip || '';
  tab.td_.innerHTML = tabName;
  tab.td_.className = this.cascade_('tablib_unselected');
  tab.td_.onclick = this.setSelectedTabGenerator_(tab);

  if (!tab.contentContainer_) {
    tab.contentContainer_ = document.createElement('div');
    tab.contentContainer_.id = this.mainContainer_.id + '_' + this.tabsAdded_;
    this.mainContainer_.appendChild(tab.contentContainer_);
  } else if (tab.contentContainer_.parentNode !== this.mainContainer_) {
    this.mainContainer_.appendChild(tab.contentContainer_);
  }
  tab.contentContainer_.style.display = 'none';
  tab.contentContainer_.className = this.cascade_('tablib_content_container') +
      ' ' + tab.contentContainer_.className;
  return tab;
};

/**
 * Helper method that creates a function to select the specified tab.
 * @param {gadgets.Tab} tab The tab to select.
 * @return {Function} Callback function to select the tab.
 */
gadgets.TabSet.prototype.setSelectedTabGenerator_ = function(tab) {
  return function() { tab.handle_.selectTab_(tab); };
};

/**
 * Helper method that selects a tab and unselects the previously selected.
 * If the tab is already selected, then callback is not executed.
 * @param {gadgets.Tab} tab The tab to select.
 */
gadgets.TabSet.prototype.selectTab_ = function(tab) {
  if (this.selectedTab_ === tab) {
    return;
  }

  if (this.selectedTab_) {
    this.selectedTab_.td_.className = this.cascade_('tablib_unselected');
    this.selectedTab_.td_.onclick =
        this.setSelectedTabGenerator_(this.selectedTab_);
    this.selectedTab_.contentContainer_.style.display = 'none';
  }

  tab.td_.className = this.cascade_('tablib_selected');
  tab.td_.onclick = null;
  tab.contentContainer_.style.display = 'block';
  this.selectedTab_ = tab;

  if (typeof tab.callback_ === 'function') {
    tab.callback_(tab.contentContainer_.id);
  }
};

// Aliases for legacy code

var _IG_Tabs = gadgets.TabSet;
_IG_Tabs.prototype.moveTab = _IG_Tabs.prototype.swapTabs;
_IG_Tabs.prototype.addDynamicTab = function(tabName, callback) {
  return this.addTab(tabName, {callback: callback});
};

