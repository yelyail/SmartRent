import "./bootstrap";
import "./register";

// Import and initialize properties
import {
    initProperties,
    filterProperties,
    openViewPropertyModal,
    openEditPropertyModal,
    closeViewPropertyModal,
    closeEditPropertyModal,
} from "./properties.js";
import {
    openAddUnitModal,
    addPropertyUnit,
    editPropertyUnit,
    deletePropertyUnit,
    switchEditTab,
    refreshUnitsList,
} from "./unit.js";
import {
    openAddDeviceModal,
    addSmartDevice,
    editSmartDevice,
    deleteSmartDevice,
    refreshDevicesList,
} from "./device.js";

// Make functions globally available for onclick attributes
window.openViewModal = openViewPropertyModal;
window.openEditModal = openEditPropertyModal;
window.openAddUnitModal = openAddUnitModal;
window.editUnit = editPropertyUnit;
window.deleteUnit = deletePropertyUnit;
window.openAddDeviceModal = openAddDeviceModal;
window.editDevice = editSmartDevice;
window.deleteDevice = deleteSmartDevice;
window.closeViewModal = closeViewPropertyModal;
window.closeEditModal = closeEditPropertyModal;
window.switchEditTab = switchEditTab;

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    initProperties();
    console.log("Properties management system loaded");
});
