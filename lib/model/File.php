<?php

class File extends BaseFile
{
  public function __toString()
  {
    return (string)$this->getName();
  }
}
