window.onload = function () {
    
    setInterval(changeImage, 5000)
  
    function changeImage () {
      let div = document.getElementById('home-page')

      let backgroundImages = [
        '../images/Image01.jpeg',
        '../images/Image02.jpeg',
        '../images/Image03.jpeg',
        '../images/Image04.jpeg',
        '../images/Image05.jpeg',
        '../images/Image06.jpeg'
      ]
      let i = Math.floor((Math.random() * 6))
      
      div.style.backgroundImage = "url("+backgroundImages[i]+")"
      console.log(backgroundImages[i])
    }
    
  }