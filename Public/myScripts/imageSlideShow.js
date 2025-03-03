// * Dependencies * 

// this function requires the following snippets:

// JavaScript/images/switchImage

//

// BODY Example:

// <body onLoad="mySlideShow1.play();">

// <img src="../Images/slideShowImage1.jpg" name="AdImage">

// <img src="originalImage2.gif" name="slide2">

//

// SCRIPT Example:

var mySlideList1 = ['../Images/slideShowImage11.jpg', '../Images/slideShowImage22.jpg', '../Images/slideShowImage33.jpg', '../Images/slideShowImage44.jpg'];

var mySlideShow1 = new SlideShow(mySlideList1, 'AdImage', 3000, "mySlideShow1");


function switchImage(imgName, imgSrc){

  if (document.images)

  {

    if (imgSrc != "none")

    {

      document.images[imgName].src = imgSrc;

    }

  }

}


// var mySlideList2 = ['image4.gif', 'image5.gif', 'image6.gif'];

// var mySlideShow2 = new SlideShow(mySlideList2, 'slide2', 1000, "mySlideShow2");

function SlideShow(slideList, image, speed, name){

  this.slideList = slideList;

  this.image = image;

  this.speed = speed;

  this.name = name;

  this.current = 0;

  this.timer = 0;

}

SlideShow.prototype.play = SlideShow_play;  

function SlideShow_play(){

  with(this)

  {

    if(current++ == slideList.length-1) current = 0;

    switchImage(image, slideList[current]);

    clearTimeout(timer);

    timer = setTimeout(name+'.play()', speed);

  }

}

// JavaScript Document