<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

class Shindig_Gadget {
  const DEFAULT_VIEW = 'profile';

  /**
   * @var GadgetSpec
   */
  public $gadgetSpec;

  public $features;
  public $substitutions;
  public $rightToLeft;

  /**
   * @var GadgetContext
   */
  public $gadgetContext;

  public function __construct(GadgetSpec $gadgetSpec, GadgetContext $gadgetContext) {
    $this->gadgetSpec = $gadgetSpec;
    $this->gadgetContext = $gadgetContext;
  }

  public function getView($viewName) {
    if (isset($this->gadgetSpec->views[$viewName])) {
      return $this->gadgetSpec->views[$viewName];
    } elseif (isset($this->gadgetSpec->views[self::DEFAULT_VIEW])) {
      return $this->gadgetSpec->views[self::DEFAULT_VIEW];
    } else {
      // see if there's any empty entries, we'll use that as default then (old iGoogle style)
      foreach ($this->gadgetSpec->views as $view) {
        if (empty($view['view'])) {
          return $view;
        }
      }
    }
    throw new GadgetException("Invalid view specified for this gadget");
  }

  /**
   * @return unknown
   */
  public function getAuthor() {
    return $this->substitutions->substitute($this->gadgetSpec->author);
  }

  /**
   * @return unknown
   */
  public function getAuthorAboutme() {
    return $this->substitutions->substitute($this->gadgetSpec->authorAboutme);
  }

  /**
   * @return unknown
   */
  public function getAuthorAffiliation() {
    return $this->substitutions->substitute($this->gadgetSpec->authorAffiliation);
  }

  /**
   * @return unknown
   */
  public function getAuthorEmail() {
    return $this->substitutions->substitute($this->gadgetSpec->authorEmail);
  }

  /**
   * @return unknown
   */
  public function getAuthorLink() {
    return $this->substitutions->substitute($this->gadgetSpec->authorLink);
  }

  /**
   * @return unknown
   */
  public function getAuthorLocation() {
    return $this->substitutions->substitute($this->gadgetSpec->authorLocation);
  }

  /**
   * @return unknown
   */
  public function getAuthorPhoto() {
    return $this->substitutions->substitute($this->gadgetSpec->authorPhoto);
  }

  /**
   * @return unknown
   */
  public function getAuthorQuote() {
    return $this->substitutions->substitute($this->gadgetSpec->authorQuote);
  }

  /**
   * @return unknown
   */
  public function getCategory() {
    return $this->substitutions->substitute($this->gadgetSpec->category);
  }

  /**
   * @return unknown
   */
  public function getCategory2() {
    return $this->substitutions->substitute($this->gadgetSpec->category2);
  }

  /**
   * @return unknown
   */
  public function getChecksum() {
    return $this->gadgetSpec->checksum;
  }

  /**
   * @return unknown
   */
  public function getDescription() {
    return $this->substitutions->substitute($this->gadgetSpec->description);
  }

  /**
   * @return unknown
   */
  public function getDirectoryTitle() {
    return $this->substitutions->substitute($this->gadgetSpec->directoryTitle);
  }

  /**
   * @return unknown
   */
  public function getHeight() {
    return $this->substitutions->substitute($this->gadgetSpec->height);
  }

  /**
   * @return unknown
   */
  public function getIcon() {
    return $this->substitutions->substitute($this->gadgetSpec->icon);
  }

  /**
   * @return unknown
   */
  public function getLinks() {
    return $this->gadgetSpec->links;
  }

  /**
   * @return unknown
   */
  public function getLocales() {
    return $this->gadgetSpec->locales;
  }

  /**
   * @return unknown
   */
  public function getOptionalFeatures() {
    return $this->gadgetSpec->optionalFeatures;
  }

  /**
   * @return unknown
   */
  public function getPreloads() {
    return $this->gadgetSpec->preloads;
  }

  /**
   * @return unknown
   */
  public function getRenderInline() {
    return $this->substitutions->substitute($this->gadgetSpec->renderInline);
  }

  /**
   * @return unknown
   */
  public function getRequiredFeatures() {
    return $this->substitutions->substitute($this->gadgetSpec->requiredFeatures);
  }

  /**
   * @return unknown
   */
  public function getScaling() {
    return $this->substitutions->substitute($this->gadgetSpec->scaling);
  }

  /**
   * @return unknown
   */
  public function getScreenshot() {
    return $this->substitutions->substitute($this->gadgetSpec->screenshot);
  }

  /**
   * @return unknown
   */
  public function getScrolling() {
    return $this->substitutions->substitute($this->gadgetSpec->scrolling);
  }

  /**
   * @return unknown
   */
  public function getShowInDirectory() {
    return $this->substitutions->substitute($this->gadgetSpec->showInDirectory);
  }

  /**
   * @return unknown
   */
  public function getShowStats() {
    return $this->substitutions->substitute($this->gadgetSpec->showStats);
  }

  /**
   * @return unknown
   */
  public function getSingleton() {
    return $this->substitutions->substitute($this->gadgetSpec->singleton);
  }

  /**
   * @return unknown
   */
  public function getString() {
    return $this->substitutions->substitute($this->gadgetSpec->string);
  }

  /**
   * @return unknown
   */
  public function getThumbnail() {
    return $this->substitutions->substitute($this->gadgetSpec->thumbnail);
  }

  /**
   * @return unknown
   */
  public function getTitle() {
    return $this->substitutions->substitute($this->gadgetSpec->title);
  }

  /**
   * @return unknown
   */
  public function getTitleUrl() {
    return $this->substitutions->substitute($this->gadgetSpec->titleUrl);
  }

  /**
   * @return unknown
   */
  public function getUserPrefs() {
    return $this->gadgetSpec->userPrefs;
  }

  /**
   * @return unknown
   */
  public function getWidth() {
    return $this->substitutions->substitute($this->gadgetSpec->width);
  }

  /**
   * @param unknown_type $author
   */
  public function setAuthor($author) {
    $this->gadgetSpec->author = $author;
  }

  /**
   * @param unknown_type $authorAboutme
   */
  public function setAuthorAboutme($authorAboutme) {
    $this->gadgetSpec->authorAboutme = $authorAboutme;
  }

  /**
   * @param unknown_type $authorAffiliation
   */
  public function setAuthorAffiliation($authorAffiliation) {
    $this->gadgetSpec->authorAffiliation = $authorAffiliation;
  }

  /**
   * @param unknown_type $authorEmail
   */
  public function setAuthorEmail($authorEmail) {
    $this->gadgetSpec->authorEmail = $authorEmail;
  }

  /**
   * @param unknown_type $authorLink
   */
  public function setAuthorLink($authorLink) {
    $this->gadgetSpec->authorLink = $authorLink;
  }

  /**
   * @param unknown_type $authorLocation
   */
  public function setAuthorLocation($authorLocation) {
    $this->gadgetSpec->authorLocation = $authorLocation;
  }

  /**
   * @param unknown_type $authorPhoto
   */
  public function setAuthorPhoto($authorPhoto) {
    $this->gadgetSpec->authorPhoto = $authorPhoto;
  }

  /**
   * @param unknown_type $authorQuote
   */
  public function setAuthorQuote($authorQuote) {
    $this->gadgetSpec->authorQuote = $authorQuote;
  }

  /**
   * @param unknown_type $category
   */
  public function setCategory($category) {
    $this->gadgetSpec->category = $category;
  }

  /**
   * @param unknown_type $category2
   */
  public function setCategory2($category2) {
    $this->gadgetSpec->category2 = $category2;
  }

  /**
   * @param unknown_type $checksum
   */
  public function setChecksum($checksum) {
    $this->gadgetSpec->checksum = $checksum;
  }

  /**
   * @param unknown_type $description
   */
  public function setDescription($description) {
    $this->gadgetSpec->description = $description;
  }

  /**
   * @param unknown_type $directoryTitle
   */
  public function setDirectoryTitle($directoryTitle) {
    $this->gadgetSpec->directoryTitle = $directoryTitle;
  }

  /**
   * @param unknown_type $height
   */
  public function setHeight($height) {
    $this->gadgetSpec->height = $height;
  }

  /**
   * @param unknown_type $icon
   */
  public function setIcon($icon) {
    $this->gadgetSpec->icon = $icon;
  }

  /**
   * @param unknown_type $links
   */
  public function setLinks($links) {
    $this->gadgetSpec->links = $links;
  }

  /**
   * @param unknown_type $locales
   */
  public function setLocales($locales) {
    $this->gadgetSpec->locales = $locales;
  }

  /**
   * @param unknown_type $optionalFeatures
   */
  public function setOptionalFeatures($optionalFeatures) {
    $this->gadgetSpec->optionalFeatures = $optionalFeatures;
  }

  /**
   * @param unknown_type $preloads
   */
  public function setPreloads($preloads) {
    $this->gadgetSpec->preloads = $preloads;
  }

  /**
   * @param unknown_type $renderInline
   */
  public function setRenderInline($renderInline) {
    $this->gadgetSpec->renderInline = $renderInline;
  }

  /**
   * @param unknown_type $requiredFeatures
   */
  public function setRequiredFeatures($requiredFeatures) {
    $this->gadgetSpec->requiredFeatures = $requiredFeatures;
  }

  /**
   * @param unknown_type $scaling
   */
  public function setScaling($scaling) {
    $this->gadgetSpec->scaling = $scaling;
  }

  /**
   * @param unknown_type $screenshot
   */
  public function setScreenshot($screenshot) {
    $this->gadgetSpec->screenshot = $screenshot;
  }

  /**
   * @param unknown_type $scrolling
   */
  public function setScrolling($scrolling) {
    $this->gadgetSpec->scrolling = $scrolling;
  }

  /**
   * @param unknown_type $showInDirectory
   */
  public function setShowInDirectory($showInDirectory) {
    $this->gadgetSpec->showInDirectory = $showInDirectory;
  }

  /**
   * @param unknown_type $showStats
   */
  public function setShowStats($showStats) {
    $this->gadgetSpec->showStats = $showStats;
  }

  /**
   * @param unknown_type $singleton
   */
  public function setSingleton($singleton) {
    $this->gadgetSpec->singleton = $singleton;
  }

  /**
   * @param unknown_type $string
   */
  public function setString($string) {
    $this->gadgetSpec->string = $string;
  }

  /**
   * @param unknown_type $thumbnail
   */
  public function setThumbnail($thumbnail) {
    $this->gadgetSpec->thumbnail = $thumbnail;
  }

  /**
   * @param unknown_type $title
   */
  public function setTitle($title) {
    $this->gadgetSpec->title = $title;
  }

  /**
   * @param unknown_type $titleUrl
   */
  public function setTitleUrl($titleUrl) {
    $this->gadgetSpec->titleUrl = $titleUrl;
  }

  /**
   * @param unknown_type $userPrefs
   */
  public function setUserPrefs($userPrefs) {
    $this->gadgetSpec->userPrefs = $userPrefs;
  }

  /**
   * @param unknown_type $width
   */
  public function setWidth($width) {
    $this->gadgetSpec->width = $width;
  }
}
