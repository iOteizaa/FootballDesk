document.addEventListener("DOMContentLoaded", () => {
    // Editar
    const editButtons = document.querySelectorAll(".edit-player-btn");
    const editId = document.getElementById("editPlayerId");
    const editName = document.getElementById("editPlayerName");
    const editPosition = document.getElementById("editPlayerPosition");
    const editAge = document.getElementById("editPlayerAge");
    const editTeam = document.getElementById("editPlayerTeam");

    editButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            editId.value = btn.dataset.id;
            editName.value = btn.dataset.name;
            editPosition.value = btn.dataset.position;
            editAge.value = btn.dataset.age;
            editTeam.value = btn.dataset.teamId;
        });
    });

    // Borrar
    const deleteButtons = document.querySelectorAll(".delete-player-btn");
    const deleteName = document.getElementById("deletePlayerName");
    const deleteId = document.getElementById("deletePlayerId");

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            deleteName.textContent = btn.dataset.name;
            deleteId.value = btn.dataset.id;
        });
    });

});