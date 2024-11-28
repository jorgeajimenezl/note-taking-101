<div id="contributorDialog" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-1/2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Share Task</h2>
            <button onclick="toggleContributorDialog(false)" class="text-gray-500 hover:text-gray-700" type="button">&times;</button>
        </div>
        <div class="flex flex-wrap gap-2 mb-4" id="contributors-container">
            <!-- Selected Contributors Go Here -->
        </div>
        <input id="contributorEmail" type="email" placeholder="Enter contributor's email..." 
               class="border rounded w-full px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
        <button type="button" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded" onclick="validateContributorEmail()">Add Contributor</button>
        <!-- <div id="contributorSuggestions" class="mt-4 max-h-60 overflow-y-auto"> -->
            <!-- Filtered Contributor Suggestions Go Here -->
        <!-- </div> -->
    </div>
</div>

<script>
    let contributors = {!! json_encode($contributors) !!};

    function toggleContributorDialog(show) {
        const dialog = document.getElementById('contributorDialog');
        dialog.classList.toggle('hidden', !show);
    }

    function validateContributorEmail() {
        const email = document.getElementById('contributorEmail').value;
        fetch(`/validate-contributor-email?email=${email}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    addExistingContributor(data.contributor);
                } else {
                    alert('Contributor not found');
                }
            });
    }

    function addExistingContributor(contributor) {
        contributors.push(contributor);
        renderContributors();
        document.getElementById('contributorEmail').value = '';
    }

    function renderContributors() {
        const contributorsContainer = document.getElementById('contributors-container');
        contributorsContainer.innerHTML = '';
        
        const colors = ['bg-red-500', 'bg-green-500', 'bg-blue-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];

        contributors.forEach(contributor => {
            var hash = 0, chr;
            for (var i = 0; i < this.length; i++) {
                chr = this.charCodeAt(i);
                hash = ((hash << 5) - hash) + chr;
                hash |= 0;
            }

            const contributorElement = `
                <div class="flex items-center space-x-2">
                    <button type="button" class="w-8 h-8 rounded-full ${colors[hash % colors.length]} flex items-center justify-center text-white relative" onclick="removeContributor(${contributor.id})">
                        <span class="initials">${contributor.name.substring(0, 2).toUpperCase()}</span>
                        <span class="remove-icon hidden absolute">&times;</span>
                    </button>
                    <input type="hidden" data-mark="input-field" name="contributors[]" value="${contributor.id}">
                </div>`;
            contributorsContainer.innerHTML += contributorElement;
        });
    }

    function removeContributor(contributorId) {
        contributors = contributors.filter(contributor => contributor.id !== contributorId);
        renderContributors();
    }
</script>

<style>
    .remove-icon {
        display: none;
    }
    .initials {
        display: block;
    }
    button:hover .initials {
        display: none;
    }
    button:hover .remove-icon {
        display: block;
    }
</style>