/**
 * UtileVidNavWP - Admin JavaScript
 * 
 * This script provides the functionality to dynamically add, remove, and save button data in the UtileVidNavWP plugin admin settings page.
 * 
 * @package UtileVidNavWP
 * @author Utilewebsites.nl /Pascal Schardijn
 * @version 1.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get the container where the buttons will be added
    const container = document.getElementById('utilevidnavwp-buttons-container');
    // Get the button that will add new button fields
    const addButton = document.getElementById('utilevidnavwp-add-button');
    
    // Function to add a new button field
    function addButtonField(name = '', time = '') {
        // HTML structure for a button field
        const html = `
            <div class="utilevidnavwp-button-field">
                <input type="text" placeholder="Button Name" value="${name}" />
                <input type="number" placeholder="Time (seconds)" value="${time}" />
                <button type="button" class="utilevidnavwp-remove-button">Remove</button>
            </div>
        `;
        // Add the new button field to the container
        container.insertAdjacentHTML('beforeend', html);
    }

    // Add existing buttons (fetched from the WordPress database)
    utilevidnavwpData.buttons.forEach(button => addButtonField(button.name, button.time));

    // Event listener for the add button
    addButton.addEventListener('click', function() {
        addButtonField(); // Add a new button field when clicked
    });

    // Event listener for remove buttons
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('utilevidnavwp-remove-button')) {
            e.target.parentElement.remove(); // Remove the button field when the remove button is clicked
        }
    });

    // Serialize and save the button data when the form is submitted
    const form = document.querySelector('#utilevidnavwp-settings-form'); // Selecteer het formulier op basis van het ID
    form.addEventListener('submit', function(event) {
       
        const buttonData = [];
        document.querySelectorAll('.utilevidnavwp-button-field').forEach(function(field) {
            const name = field.querySelector('input[type="text"]').value;
            const time = field.querySelector('input[type="number"]').value;
            buttonData.push({ name, time });
        });
        const buttonDataJson = JSON.stringify(buttonData);
        console.log(buttonDataJson);
        const buttonsJsonInput = document.getElementById('utilevidnavwp-buttons-json');
        buttonsJsonInput.value = buttonDataJson;
    });
    
});
