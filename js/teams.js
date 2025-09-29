document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll(".delete-team-btn");

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const teamId = this.dataset.id;
            const teamName = this.dataset.name;

            document.getElementById("deleteTeamId").value = teamId;
            document.getElementById("deleteTeamName").textContent = teamName;
        });
    });
});