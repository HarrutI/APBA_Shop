/*
  This function toggles the visibility of the filters container.
  It first retrieves the filters container element using its ID.
  Then it checks if the container already has a set min-height.
  If it does, it removes the min-height to make the container hidden again.
  If it doesn't, it calculates the scroll height of the container
  (which is the total height of all its content, including overflowed content),
  adds 10 pixels to it (to provide some space below the container),
  and sets the min-height of the container to this calculated value,
  which will make the container visible and scrollable if its content exceeds its height.
*/
function toggleFilters() {
    // Get the filters container element
    let filtersContainer = document.getElementById('filters-container');

    // Check if the container already has a set min-height
    if (filtersContainer.style.minHeight) {
        // If it does, remove the min-height to hide the container
        filtersContainer.style.minHeight = null;
    } else {
        // If it doesn't, calculate the min-height and set it to show the container
        filtersContainer.style.minHeight = (filtersContainer.scrollHeight + 10) + "px";
    }
}
