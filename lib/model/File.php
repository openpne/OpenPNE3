<?php

class File extends BaseFile
{
  function __toString()
  {
    return $this->getName();
  }
}
