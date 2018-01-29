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

if (! class_exists('Enum')) {
  require 'Enum.php';
}

/**
 * see
 * http://www.opensocial.org/Technical-Resources/opensocial-spec-v081/opensocial-reference#opensocial.Person
 *
 */
class Person {
  public $aboutMe;
  public $accounts;
  public $activities;
  public $addresses;
  public $age;
  public $birthday;
  public $bodyType;
  public $books;
  public $cars;
  public $children;
  public $currentLocation;
  public $displayName;
  public $drinker;
  public $emails;
  public $ethnicity;
  public $fashion;
  public $food;
  public $gender;
  public $happiestWhen;
  public $hasApp;
  public $heroes;
  public $humor;
  public $id;
  public $ims;
  public $interests;
  public $jobInterests;
  public $languagesSpoken;
  public $livingArrangement;
  public $lookingFor;
  public $movies;
  public $music;
  public $organizations;
  public $name;
  public $networkPresence;
  public $nickname;
  public $pets;
  public $phoneNumbers;
  public $photos;
  public $politicalViews;
  public $profileSong;
  public $profileUrl;
  public $profileVideo;
  public $quotes;
  public $relationshipStatus;
  public $religion;
  public $romance;
  public $scaredOf;
  public $sexualOrientation;
  public $smoker;
  public $sports;
  public $status;
  public $tags;
  public $thumbnailUrl;
  public $utcOffset;
  public $turnOffs;
  public $turnOns;
  public $tvShows;
  public $urls;

  // Note: Not in the opensocial js person object directly
  public $isOwner = false;
  public $isViewer = false;

  public function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
  }

  private function setFieldImpl($fieldName, $value) {
    // treat empty singular/plural fields as null so they don't pollute the output
    if ($value === '' || (is_array($value) && ! count($value))) {
      $value = null;
    }
    $this->$fieldName = $value;
  }

  /**
   * Returns the field value for the given fieldName, if present.
   * @param $fieldName name of the contact field, e.g. "displayName"
   */
  public function getFieldByName($fieldName) {
    if (isset($this->$fieldName)) {
      return $this->$fieldName;
    }
    return null;
  }

  public function getAboutMe() {
    return $this->aboutMe;
  }

  public function setAboutMe($aboutMe) {
    $this->setFieldImpl('aboutMe', $aboutMe);
  }

  public function getAccounts() {
    return $this->accounts;
  }

  public function setAccounts($accounts) {
    $this->setFieldImpl('accounts', $accounts);
  }

  public function getActivities() {
    return $this->activities;
  }

  public function setActivities($activities) {
    $this->setFieldImpl('activities', $activities);
  }

  public function getAddresses() {
    return $this->addresses;
  }

  public function setAddresses($addresses) {
    $this->setFieldImpl('addresses', $addresses);
  }

  public function getAge() {
    return $this->age;
  }

  public function setAge($age) {
    $this->setFieldImpl('age', $age);
  }

  public function getBirthday() {
    return $this->birthday;
  }

  public function setBirthday($birthday) {
    $birthday = date('Y-m-d', strtotime($birthday));
    $this->setFieldImpl('birthday', $birthday);
  }

  public function getBodyType() {
    return $this->bodyType;
  }

  public function setBodyType($bodyType) {
    $this->setFieldImpl('bodyType', $bodyType);
  }

  public function getBooks() {
    return $this->books;
  }

  public function setBooks($books) {
    $this->setFieldImpl('books', $books);
  }

  public function getCars() {
    return $this->cars;
  }

  public function setCars($cars) {
    $this->setFieldImpl('cars', $cars);
  }

  public function getChildren() {
    return $this->children;
  }

  public function setChildren($children) {
    $this->setFieldImpl('children', $children);
  }

  public function getCurrentLocation() {
    return $this->currentLocation;
  }

  public function setCurrentLocation($currentLocation) {
    $this->setFieldImpl('currentLocation', $currentLocation);
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName($displayName) {
    $this->setFieldImpl('displayName', $displayName);
  }

  public function getDrinker() {
    return $this->drinker;
  }

  public function setDrinker($drinker) {
    $this->setFieldImpl('drinker', $drinker);
  }

  public function getEmails() {
    return $this->emails;
  }

  public function setEmails($emails) {
    $this->setFieldImpl('emails', $emails);
  }

  public function getEthnicity() {
    return $this->ethnicity;
  }

  public function setEthnicity($ethnicity) {
    $this->setFieldImpl('ethnicity', $ethnicity);
  }

  public function getFashion() {
    return $this->fashion;
  }

  public function setFashion($fashion) {
    $this->setFieldImpl('fashion', $fashion);
  }

  public function getFood() {
    return $this->food;
  }

  public function setFood($food) {
    $this->setFieldImpl('food', $food);
  }

  public function getGender() {
    return $this->gender;
  }

  public function setGender($gender) {
    $this->setFieldImpl('gender', $gender);
  }

  public function getHappiestWhen() {
    return $this->happiestWhen;
  }

  public function setHappiestWhen($happiestWhen) {
    $this->setFieldImpl('happiestWhen', $happiestWhen);
  }

  public function getHeroes() {
    return $this->heroes;
  }

  public function setHeroes($heroes) {
    $this->setFieldImpl('heroes', $heroes);
  }

  public function getHasApp() {
    return $this->hasApp;
  }

  public function setHasApp($hasApp) {
    $this->setFieldImpl('hasApp', $hasApp);
  }

  public function getHumor() {
    return $this->humor;
  }

  public function setHumor($humor) {
    $this->setFieldImpl('humor', $humor);
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->setFieldImpl('id', $id);
  }

  public function getIms() {
    return $this->ims;
  }

  public function setIms($ims) {
    $this->setFieldImpl('ims', $ims);
  }

  public function getInterests() {
    return $this->interests;
  }

  public function setInterests($interests) {
    $this->setFieldImpl('interests', $interests);
  }

  public function getJobInterests() {
    return $this->jobInterests;
  }

  public function setJobInterests($jobInterests) {
    $this->setFieldImpl('jobInterests', $jobInterests);
  }

  public function getLanguagesSpoken() {
    return $this->languagesSpoken;
  }

  public function setLanguagesSpoken($languagesSpoken) {
    $this->setFieldImpl('languagesSpoken', $languagesSpoken);
  }

  public function getLivingArrangement() {
    return $this->livingArrangement;
  }

  public function setLivingArrangement($livingArrangement) {
    $this->setFieldImpl('livingArrangement', $livingArrangement);
  }

  public function getLookingFor() {
    return $this->lookingFor;
  }

  public function setLookingFor($lookingFor) {
    $this->setFieldImpl('lookingFor', new EnumLookingFor($lookingFor));
  }

  public function getMovies() {
    return $this->movies;
  }

  public function setMovies($movies) {
    $this->setFieldImpl('movies', $movies);
  }

  public function getMusic() {
    return $this->music;
  }

  public function setMusic($music) {
    $this->setFieldImpl('music', $music);
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->setFieldImpl('name', $name);
  }

  public function getNetworkPresence() {
    return $this->networkPresence;
  }

  public function setNetworkPresence($networkPresence) {
    $this->setFieldImpl('networkPresence', new EnumPresence($networkPresence));
  }

  public function getNickname() {
    return $this->nickname;
  }

  public function setNickname($nickname) {
    $this->nickname = $nickname;
    $this->setFieldImpl('nickname', $nickname);
  }

  public function getOrganizations() {
    return $this->organizations;
  }

  public function setOrganizations($organizations) {
    $this->setFieldImpl('organizations', $organizations);
  }

  public function getPets() {
    return $this->pets;
  }

  public function setPets($pets) {
    $this->setFieldImpl('pets', $pets);
  }

  public function getPhoneNumbers() {
    return $this->phoneNumbers;
  }

  public function setPhoneNumbers($phoneNumbers) {
    $this->setFieldImpl('phoneNumbers', $phoneNumbers);
  }

  public function getPhotos() {
    return $this->photos;
  }

  public function setPhotos($photos) {
    $this->setFieldImpl('photos', $photos);
  }

  public function getPoliticalViews() {
    return $this->politicalViews;
  }

  public function setPoliticalViews($politicalViews) {
    $this->setFieldImpl('politicalViews', $politicalViews);
  }

  public function getProfileSong() {
    return $this->profileSong;
  }

  public function setProfileSong($profileSong) {
    $this->setFieldImpl('profileSong', $profileSong);
  }

  public function getProfileUrl() {
    return $this->profileUrl;
  }

  public function setProfileUrl($profileUrl) {
    $this->setFieldImpl('profileUrl', $profileUrl);
  }

  public function getProfileVideo() {
    return $this->profileVideo;
  }

  public function setProfileVideo($profileVideo) {
    $this->setFieldImpl('profileVideo', $profileVideo);
  }

  public function getQuotes() {
    return $this->quotes;
  }

  public function setQuotes($quotes) {
    $this->setFieldImpl('quotes', $quotes);
  }

  public function getRelationshipStatus() {
    return $this->relationshipStatus;
  }

  public function setRelationshipStatus($relationshipStatus) {
    $this->setFieldImpl('relationshipStatus', $relationshipStatus);
  }

  public function getReligion() {
    return $this->religion;
  }

  public function setReligion($religion) {
    $this->religion = $religion;
  }

  public function getRomance() {
    return $this->romance;
  }

  public function setRomance($romance) {
    $this->setFieldImpl('romance', $romance);
  }

  public function getScaredOf() {
    return $this->scaredOf;
  }

  public function setScaredOf($scaredOf) {
    $this->setFieldImpl('scaredOf', $scaredOf);
  }

  public function getSexualOrientation() {
    return $this->sexualOrientation;
  }

  public function setSexualOrientation($sexualOrientation) {
    $this->setFieldImpl('sexualOrientation', $sexualOrientation);
  }

  public function getSmoker() {
    return $this->smoker;
  }

  public function setSmoker($smoker) {
    $this->setFieldImpl('smoker', new EnumSmoker($smoker));
  }

  public function getSports() {
    return $this->sports;
  }

  public function setSports($sports) {
    $this->setFieldImpl('sports', $sports);
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus($status) {
    $this->setFieldImpl('status', $status);
  }

  public function getTags() {
    return $this->tags;
  }

  public function setTags($tags) {
    $this->setFieldImpl('tags', $tags);
  }

  public function getThumbnailUrl() {
    return $this->thumbnailUrl;
  }

  public function setThumbnailUrl($thumbnailUrl) {
    $this->setFieldImpl('thumbnailUrl', $thumbnailUrl);
  }

  public function getUtcOffset() {
    return $this->utcOffset;
  }

  public function setUtcOffset($utcOffset) {
    // TODO: validate +00:00 format here?
    $sign = ($utcOffset >= 0) ? "+" : "-";
    $utcOffset = date('h:i', strtotime($utcOffset));
    $utcOffset = $sign . $utcOffset;
    $this->setFieldImpl('utcOffset', $utcOffset);
  }

  public function getTurnOffs() {
    return $this->turnOffs;
  }

  public function setTurnOffs($turnOffs) {
    $this->setFieldImpl('turnOffs', $turnOffs);
  }

  public function getTurnOns() {
    return $this->turnOns;
  }

  public function setTurnOns($turnOns) {
    $this->setFieldImpl('turnOns', $turnOns);
  }

  public function getTvShows() {
    return $this->tvShows;
  }

  public function setTvShows($tvShows) {
    $this->setFieldImpl('tvShows', $tvShows);
  }

  public function getUrls() {
    return $this->urls;
  }

  public function setUrls($urls) {
    $this->setFieldImpl('urls', $urls);
  }

  public function getIsOwner() {
    return $this->isOwner;
  }

  public function setIsOwner($isOwner) {
    $this->setFieldImpl('isOwner', $isOwner);
  }

  public function getIsViewer() {
    return $this->isViewer;
  }

  public function setIsViewer($isViewer) {
    $this->setFieldImpl('isViewer', $isViewer);
  }
}
