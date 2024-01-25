/**
 * UtileVidNavWP - front JavaScript
 * 
 * This script provides the functionality to the video navigation buttons on the front-end.
 * 
 * @package UtileVidNavWP
 * @author Utilewebsites.nl/Pascal Schardijn
 * @version 1.0
 */


var player;


function initializeYouTubePlayer(videoId) {
    console.log(videoId);
    player = new YT.Player('utilevidnavwp-player', {
        height: '390',
        width: '640',
        videoId: videoId, 
        events: {
            'onReady': onPlayerReady
        }
    });
}

function onPlayerReady(event) {

      // Get a reference to the element
      var buttonsContainer = document.getElementById("utilevidnavwp-buttons-container");

      // Apply the fadeIn effect using CSS transitions
      buttonsContainer.style.opacity = "0";
      buttonsContainer.style.display = "block";
  
      // Trigger a reflow to enable the transition
      buttonsContainer.offsetWidth;
  
      // Apply the fadeIn effect by changing opacity with a slower duration
      buttonsContainer.style.transition = "opacity 2s"; // Adjust the duration here (e.g., 2s for 2 seconds)
      buttonsContainer.style.opacity = "1";
    
}

function jumpToTime(seconds) {
    if (player && player.seekTo) {
        player.seekTo(seconds);
    }
}

// Function to check if the YouTube API script is loaded
function checkYouTubeAPILoaded() {
    if (typeof YT !== 'undefined' && typeof YT.Player !== 'undefined') {
    // YouTube API is loaded, initialize the player with the video ID
    console.log('loaded id'+videoId);
    // buttonscreated = buttonsarray;
    initializeYouTubePlayer(videoId);
    } else {
    // Wait and check again after a short delay
    setTimeout(checkYouTubeAPILoaded, 100);
    }
}

