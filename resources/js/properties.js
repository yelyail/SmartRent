async function openEditModal(propertyId) {
    // Show minimal modal first
    const skeletonHTML = `
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Loading...</h2>
                    </div>
                </div>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Tabs Skeleton -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <button id="editPropertyTab" class="tab-button border-b-2 border-blue-500 text-blue-600 px-1 py-4 text-sm font-medium">
                        Property Details
                    </button>
                    <button id="editUnitsTab" class="tab-button border-b-2 border-transparent text-gray-500 px-1 py-4 text-sm font-medium">
                        Units (0)
                    </button>
                    <button id="editDevicesTab" class="tab-button border-b-2 border-transparent text-gray-500 px-1 py-4 text-sm font-medium">
                        Smart Devices (0)
                    </button>
                </nav>
            </div>

            <!-- Content Skeleton - ADD THE MISSING CONTENT AREAS -->
            <div class="tab-content">
                <div id="editPropertyContent" class="p-6">
                    <div class="space-y-4 animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-32 bg-gray-200 rounded"></div>
                    </div>
                </div>
                
                <!-- ADD THIS: Units content area -->
                <div id="editUnitsContent" class="p-6 hidden">
                    <!-- Units will be loaded here -->
                </div>
                
                <!-- ADD THIS: Devices content area -->
                <div id="editDevicesContent" class="p-6 hidden">
                    <!-- Devices will be loaded here -->
                </div>
            </div>
        </div>
    `;

    document.getElementById("editPropertyModal").innerHTML = skeletonHTML;
    document.getElementById("editPropertyModal").classList.remove("hidden");
    document.getElementById("editPropertyModal").classList.add("flex");

    // Load property data first (fast)
    const property = await fetchProperty(propertyId);

    // Update modal with property data
    updateModalWithProperty(property);

    // Set up tab click handlers for lazy loading
    setupTabLazyLoading(propertyId);
}
function setupTabLazyLoading(propertyId) {
    // First, create the missing content areas if they don't exist
    createMissingContentAreas();

    document
        .getElementById("editUnitsTab")
        .addEventListener("click", async () => {
            // Load units only when tab is clicked
            if (!unitsCache.has(propertyId)) {
                showTabLoading("Units");
                const units = await fetchUnits(propertyId);
                renderUnitsTab(units);
            }
        });

    document
        .getElementById("editDevicesTab")
        .addEventListener("click", async () => {
            // Load devices only when tab is clicked
            if (!devicesCache.has(propertyId)) {
                showTabLoading("Devices");
                const devices = await fetchDevices(propertyId);
                renderDevicesTab(devices);
            }
        });
}

// Add this helper function to create missing content areas
function createMissingContentAreas() {
    // Check if editUnitsContent exists, if not create it
    if (!document.getElementById("editUnitsContent")) {
        const unitsContent = document.createElement("div");
        unitsContent.id = "editUnitsContent";
        unitsContent.className = "p-6 hidden";

        // Find where to insert it (after editPropertyContent)
        const propertyContent = document.getElementById("editPropertyContent");
        if (propertyContent && propertyContent.parentNode) {
            propertyContent.parentNode.appendChild(unitsContent);
        }
    }

    // Check if editDevicesContent exists, if not create it
    if (!document.getElementById("editDevicesContent")) {
        const devicesContent = document.createElement("div");
        devicesContent.id = "editDevicesContent";
        devicesContent.className = "p-6 hidden";

        // Find where to insert it (after editUnitsContent)
        const unitsContent =
            document.getElementById("editUnitsContent") ||
            document.getElementById("editPropertyContent");
        if (unitsContent && unitsContent.parentNode) {
            unitsContent.parentNode.appendChild(devicesContent);
        }
    }
}

// Update showTabLoading to ensure content areas exist
function showTabLoading(tab) {
    // First make sure content area exists
    const contentId = `edit${tab}Content`;

    // If it doesn't exist, create it
    if (!document.getElementById(contentId)) {
        createMissingContentAreas();
    }

    const contentDiv = document.getElementById(contentId);

    if (contentDiv) {
        // Hide all content areas first
        const allContents = document.querySelectorAll('[id$="Content"]');
        allContents.forEach((content) => {
            content.classList.add("hidden");
        });

        // Show loading in the requested tab
        const loadingHTML = `
            <div class="flex justify-center items-center h-32">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Loading ${tab.toLowerCase()}...</span>
            </div>
        `;

        contentDiv.innerHTML = loadingHTML;
        contentDiv.classList.remove("hidden");
    }
}
