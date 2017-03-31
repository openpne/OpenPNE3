<?php
class DoctrineTest_Reporter_Html extends DoctrineTest_Reporter
{
    public function paintHeader($name)
    {
?>
<html>
<head>
  <title>Doctrine Unit Tests</title>
  <style>
  .fail
  {
      color: red;
  }
  
  #messages
  {
      font-family: courier new, monospace;
      border-left: 1px solid #333333;
      border-right: 1px solid #333333;
      border-top: 1px solid #333333;
      background-color: #f5f5f5;
      padding: 10px;
  }
  
  #summary
  {
      border: 1px solid #333333;
      background-color: #ffc;
      padding: 8px;
      color: white;
      margin-bottom: 10px;
  }
  
  #wrapper
  {
      
  }
  
  #wrapper h1
  {
      font-size: 20pt;
      margin-bottom: 10px;
      font-weight: bold;
  }
  </style>
</head>

<body>

<div id="wrapper">
<h1><?php echo $name ?></h1>

<div id="messages">
<?php
    }

    public function paintFooter()
    {
        print '</div></div>';
        
            $this->paintSummary();
    }

    public function paintMessages()
    {
        parent::paintMessages();
    }

    public function paintSummary()
    {
        print '<div id="summary">';

        echo $this->format("Tested: " . $this->_test->getTestCaseCount() . ' test cases.', 'INFO') . "<br/>";
        echo $this->format("Successes: " . $this->_test->getPassCount() . " passes.", 'INFO') . "<br/>";
        echo $this->format("Failures: " . $this->_test->getFailCount() . " fails.", $this->_test->getFailCount() ? 'ERROR':'INFO') . "<br/>";
        echo $this->format("Number of new Failures: " . $this->_test->getNumNewFails(), $this->_test->getNumNewFails() ? 'ERROR':'INFO') . ' ' . implode(", ", $this->_test->getNewFails()) . "<br/>";
        echo $this->format("Number of fixed Failures: " . $this->_test->getNumFixedFails(), $this->_test->getNumFixedFails() ? 'INFO':'HEADER') . ' ' . implode(", ", $this->_test->getFixedFails()) . "<br/>";

        print '</div>';
    }
}