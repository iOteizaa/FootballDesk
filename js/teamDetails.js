document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-team-btn");
    const modal = document.getElementById("editTeamModal");

    editButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            // Poner datos en la modal
            modal.querySelector("input[name='id']").value = this.dataset.id;
            modal.querySelector("input[name='name']").value = this.dataset.name;
            modal.querySelector("input[name='city']").value = this.dataset.city;
            modal.querySelector("input[name='coach']").value = this.dataset.coach;
            modal.querySelector("input[name='img']").value = this.dataset.img;
            modal.querySelector("input[name='matches_played']").value = this.dataset.matchesPlayed || 0;
            modal.querySelector("input[name='wins']").value = this.dataset.wins || 0;
            modal.querySelector("input[name='draws']").value = this.dataset.draws || 0;
            modal.querySelector("input[name='losses']").value = this.dataset.losses || 0;
            modal.querySelector("input[name='goals_for']").value = this.dataset.goalsFor || 0;
            modal.querySelector("input[name='goals_against']").value = this.dataset.goalsAgainst || 0;
            modal.querySelector("input[name='points']").value = this.dataset.points || 0;
        });
    });

    // Calcular puntos automaticamente
    function updateStats() {
        const wins = parseInt(modal.querySelector("input[name='wins']").value) || 0;
        const draws = parseInt(modal.querySelector("input[name='draws']").value) || 0;
        const losses = parseInt(modal.querySelector("input[name='losses']").value) || 0;

        const points = wins * 3 + draws * 1;
        const matches_played = wins + draws + losses;

        modal.querySelector("input[name='points']").value = points;
        modal.querySelector("input[name='matches_played']").value = matches_played;
    }

    // Añadir eventos para recalcular automáticamente
    ["wins", "draws", "losses"].forEach(field => {
        modal.querySelector(`input[name='${field}']`).addEventListener("input", updateStats);
    });
});
