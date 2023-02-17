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

import Vue from 'vue'
import './vueBootstrap.js'
import AdminSettings from './components/AdminSettings.vue'

const VueSettings = Vue.extend(AdminSettings)
new VueSettings().$mount('#tuto4_prefs')
