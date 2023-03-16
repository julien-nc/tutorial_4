<template>
	<div id="tuto4_prefs" class="section">
		<h2>
			<PexelsIcon class="icon" />
			{{ t('tutorial_4', 'Pexels integration') }}
		</h2>
		<div id="tuto4-content">
			<div class="line">
				<label for="pexels-api-key">
					<KeyIcon :size="20" class="icon" />
					{{ t('tutorial_4', 'Pexels API key') }}
				</label>
				<input id="pexels-api-key"
					v-model="state.api_key"
					type="password"
					:placeholder="t('tutorial_4', '...')"
					@input="inputChanged = true">
				<NcButton v-if="inputChanged"
					type="primary"
					@click="onSave">
					<template #icon>
						<NcLoadingIcon v-if="loading" />
						<ArrowRightIcon v-else />
					</template>
					{{ t('tutorial_4', 'Save') }}
				</NcButton>
			</div>
		</div>
	</div>
</template>

<script>
import KeyIcon from 'vue-material-design-icons/Key.vue'
import ArrowRightIcon from 'vue-material-design-icons/ArrowRight.vue'

import PexelsIcon from './icons/PexelsIcon.vue'

import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import { loadState } from '@nextcloud/initial-state'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { showSuccess, showError } from '@nextcloud/dialogs'

export default {
	name: 'AdminSettings',

	components: {
		PexelsIcon,
		KeyIcon,
		NcButton,
		NcLoadingIcon,
		ArrowRightIcon,
	},

	props: [],

	data() {
		return {
			state: loadState('tutorial_4', 'admin-config'),
			loading: false,
			inputChanged: false,
		}
	},

	computed: {
	},

	watch: {
	},

	mounted() {
	},

	methods: {
		onSave() {
			this.saveOptions({
				api_key: this.state.api_key,
			})
		},
		saveOptions(values) {
			this.loading = true
			const req = {
				values,
			}
			const url = generateUrl('/apps/tutorial_4/admin-config')
			axios.put(url, req).then((response) => {
				showSuccess(t('tutorial_4', 'Pexels options saved'))
				this.inputChanged = false
			}).catch((error) => {
				showError(
					t('tutorial_4', 'Failed to save Pexels options')
					+ ': ' + (error.response?.data?.error ?? '')
				)
				console.error(error)
			}).then(() => {
				this.loading = false
			})
		},
	},
}
</script>

<style scoped lang="scss">
#tuto4_prefs {
	#tuto4-content {
		margin-left: 40px;
	}
	h2,
	.line,
	.settings-hint {
		display: flex;
		align-items: center;
		.icon {
			margin-right: 4px;
		}
	}

	h2 .icon {
		margin-right: 8px;
	}

	.line {
		> label {
			width: 300px;
			display: flex;
			align-items: center;
		}
		> input {
			width: 300px;
		}
	}
}
</style>
