document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".edit-match-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("edit_match_id").value = this.dataset.id;
            document.getElementById("edit_home_team").value = this.dataset.home;
            document.getElementById("edit_away_team").value = this.dataset.away;
            document.getElementById("edit_match_date").value = this.dataset.date;
            document.getElementById("edit_competition").value = this.dataset.competition;
            document.getElementById("edit_stadium").value = this.dataset.stadium;
            document.getElementById("edit_home_score").value = this.dataset.homeScore || "";
            document.getElementById("edit_away_score").value = this.dataset.awayScore || "";
        });
    });

    document.querySelectorAll(".delete-match-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("delete_match_id").value = this.dataset.id;
            document.getElementById("delete_match_info").textContent =
                this.dataset.home + " vs " + this.dataset.away + " (" + this.dataset.date + ")";
        });
    });
});