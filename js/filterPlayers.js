document.addEventListener('DOMContentLoaded', function () {
    const teamFilter = document.getElementById('teamFilter');
    const positionFilter = document.getElementById('positionFilter');
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const players = document.querySelectorAll('#playersContainer .col-md-6.col-lg-4.col-xl-3.mb-4');

    function filterPlayers() {
        const teamValue = teamFilter.value.trim().toLowerCase();
        const positionValue = positionFilter.value.trim().toLowerCase();
        const searchValue = searchInput.value.trim().toLowerCase();

        players.forEach(player => {
            const team = player.getAttribute('data-team').toLowerCase();
            const position = player.getAttribute('data-position').toLowerCase();
            const playerName = player.querySelector('.card-title').textContent.toLowerCase();

            const teamMatch = teamValue === '' || team === teamValue;
            const positionMatch = positionValue === '' || position === positionValue;
            const searchMatch = playerName.includes(searchValue);

            if (teamMatch && positionMatch && searchMatch) {
                player.style.display = 'block';
            } else {
                player.style.display = 'none';
            }
        });
    }

    teamFilter.addEventListener('change', filterPlayers);
    positionFilter.addEventListener('change', filterPlayers);
    searchInput.addEventListener('input', filterPlayers);
    searchButton.addEventListener('click', filterPlayers);
});