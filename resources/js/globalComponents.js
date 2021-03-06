/**
 * Vuely Global Components
 */
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import AppSectionLoader from "Components/AppSectionLoader/AppSectionLoader";
import { RotateSquare2 } from "vue-loading-spinner";

// delete Confirmation Dialog
import DeleteConfirmationDialog from "Components/DeleteConfirmationDialog/DeleteConfirmationDialog";
import ConfirmationDialog from "Components/ConfirmationDialog/ConfirmationDialog";

// page title bar
import PageTitleBar from "Components/PageTitleBar/PageTitleBar";

//crypto slider
// import CryptoSlider from "Components/Widgets/CryptoSlider";

// App Card component
import AppCard from 'Components/AppCard/AppCard';
import CopySettingDialog from 'Components/CopySettingDialog/CopySettingDialog';

// stats card
// import StatsCard from 'Components/StatsCard/StatsCard';
// import StatsCardV2 from 'Components/StatsCardV2/StatsCardV2';

// section tooltip
// import SectionTooltip from "Components/SectionTooltip/SectionTooltip"

const GlobalComponents = {
    install(Vue) {
        Vue.component('appCard', AppCard);
        // Vue.component('sectionTooltip', SectionTooltip);
        // Vue.component('statsCard', StatsCard);
        // Vue.component('statsCardV2', StatsCardV2);
        Vue.component('deleteConfirmationDialog', DeleteConfirmationDialog);
        Vue.component('copySettingDialog', CopySettingDialog);
        Vue.component('confirmationDialog', ConfirmationDialog);
        Vue.component('vuePerfectScrollbar', VuePerfectScrollbar);
        Vue.component('appSectionLoader', AppSectionLoader);
        Vue.component('pageTitleBar', PageTitleBar);
        Vue.component('rotateSquare2', RotateSquare2);
        // Vue.component('cryptoSlider', CryptoSlider);
    }
}

export default GlobalComponents
