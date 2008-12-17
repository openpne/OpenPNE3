function getCenterMuchScreen(width, height)
{
  var screenWidth = document.body.clientWidth  || document.documentElement.clientWidth;
  var screenHeight = document.documentElement.clientHeight;
  var screenTop = document.body.scrollTop || document.documentElement.scrollTop;

  var left = (screenWidth / 2) - (width / 2);
  var top = (screenHeight / 2 + screenTop) - (height / 2);

  return {"left": left, "top" : top};
}
