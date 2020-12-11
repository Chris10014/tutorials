/*Name this external file gallery.js*/

function upDate(previewPic){
 /* In this function you should 
    1) change the url for the background image of the div with the id = "image" 
    to the source file of the preview image */
   
       x = document.getElementById('image');
    
       x.style.backgroundImage = "url('" + previewPic.src + "')";
       x.style.backgroundColor = "#ff00ff";
    
 /* 2) Change the text  of the div with the id = "image" 
    to the alt text of the preview image 
    */
       x.innerHTML = previewPic.alt;  
  
	}

	function unDo(){
     /* In this function you should 
    1) Update the url for the background image of the div with the id = "image" 
    back to the orginal-image.  You can use the css code to see what that original URL was */

       x = document.getElementById('image');
    
       x.style.backgroundImage = "url('')";
       x.style.backgroundColor = "#8e68ff";

   /*  2) Change the text  of the div with the id = "image" 
    back to the original text.  You can use the html code to see what that original text was
    */
	    x.innerHTML = "Hover over an image below to display here.";
   }
   
   function loadNames() {
     
     document.getElementById("names").innerHTML = names;
   }

   function addName() {
     var name = prompt("Neuer Name");
     names[names.length] = name;

     document.getElementById("names").innerHTML = names;
   }