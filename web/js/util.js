function getCenterMuchScreen(element)
{
  var width  = $(element).getWidth();
  var height = $(element).getHeight();
  var screenWidth = document.viewport.getWidth();
  var screenHeight = document.documentElement.clientHeight;
  var screenTop = document.viewport.getScrollOffsets().top;

  var left = (screenWidth / 2) - (width / 2);
  var top = (screenHeight / 2 + screenTop) - (height / 2);

  if (top < 10)
  {
    top = 10;
  }

  return {"left": left + "px", "top" : top + "px"};
}
