/**
 * Nextcloud - OpenStreetMap
 *
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <eneiluj@posteo.net>
 * @copyright Julien Veyssier 2023
 */

import AdminSettings from './components/AdminSettings.vue'
import Vue from 'vue'
Vue.mixin({ methods: { t, n } })

const VueSettings = Vue.extend(AdminSettings)
new VueSettings().$mount('#tuto4_prefs')
