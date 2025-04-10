// JavaScript code demonstrating basic functionality and common operations

// Function to handle form submission
// Parameters:
//   - event: The form submission event object
// Example output: Console log with form data {name: 'John', email: 'john@example.com'}
function handleSubmit(event) {
    event.preventDefault();  // Prevents the default form submission behavior
    
    // Get form field values using getElementById
    // Example: If input value is 'John', name will be 'John'
    const name = document.getElementById('name').value;    // Get value from name input
    const email = document.getElementById('email').value;  // Get value from email input
    
    // Log form data to console for demonstration
    // Shows submitted form values in an object format
    console.log('Form submitted:', { name, email });
}

// Add event listener to form element
// This sets up the form to use our custom submission handler
// Example: When form is submitted, handleSubmit function is called
const form = document.querySelector('form');              // Select the form element
form.addEventListener('submit', handleSubmit);           // Attach submit event handler

// Example of DOM manipulation using querySelector
// This demonstrates how to change element styles dynamically
// Example: Changes the main heading color to blue
const heading = document.querySelector('h1');            // Select the h1 element
heading.style.color = 'blue';                           // Change text color to blue

// Example of working with arrays and modern JavaScript features
// Demonstrates array iteration using forEach with arrow function
// Example output: Logs 'apple', 'banana', 'orange' to console
const fruits = ['apple', 'banana', 'orange'];           // Define array of fruits
fruits.forEach(fruit => console.log(fruit));            // Log each fruit to console

// Event Handling Demonstration
document.addEventListener('DOMContentLoaded', () => {
    // Click event handling
    const clickButton = document.getElementById('click-btn');
    const eventOutput = document.getElementById('event-output');
    
    clickButton.addEventListener('click', () => {
        eventOutput.textContent = 'Button clicked at: ' + new Date().toLocaleTimeString();
    });

    // Input event handling
    const inputField = document.getElementById('input-field');
    inputField.addEventListener('input', (event) => {
        eventOutput.textContent = 'You typed: ' + event.target.value;
    });

    // Mouse events demonstration
    const mouseArea = document.getElementById('mouse-area');
    mouseArea.addEventListener('mouseenter', () => {
        mouseArea.style.backgroundColor = '#e0e0e0';
        eventOutput.textContent = 'Mouse entered the area';
    });

    mouseArea.addEventListener('mouseleave', () => {
        mouseArea.style.backgroundColor = '';
        eventOutput.textContent = 'Mouse left the area';
    });

    // DOM Manipulation Demonstration
    const addButton = document.getElementById('add-btn');
    const modifyButton = document.getElementById('modify-btn');
    const styleButton = document.getElementById('style-btn');
    const dynamicContent = document.getElementById('dynamic-content');
    const contentDiv = document.getElementById('content-div');

    // Adding new elements
    addButton.addEventListener('click', () => {
        const newElement = document.createElement('p');
        newElement.textContent = 'Dynamically added at ' + new Date().toLocaleTimeString();
        dynamicContent.appendChild(newElement);
    });

    // Modifying content
    modifyButton.addEventListener('click', () => {
        contentDiv.textContent = 'Content modified at ' + new Date().toLocaleTimeString();
    });

    // Changing styles
    styleButton.addEventListener('click', () => {
        contentDiv.style.backgroundColor = getRandomColor();
        contentDiv.style.padding = '10px';
        contentDiv.style.borderRadius = '5px';
    });
});

// Utility function for generating random colors
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}