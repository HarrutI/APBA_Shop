function toggleFilters() {
    let filtersContainer = document.getElementById('filters-container');
    if (filtersContainer.style.minHeight) {
      filtersContainer.style.minHeight = null;
    } else {
      filtersContainer.style.minHeight = filtersContainer.scrollHeight + 10 + "px";
}
}
