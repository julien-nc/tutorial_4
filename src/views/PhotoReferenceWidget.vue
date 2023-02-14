<!--
  - @copyright Copyright (c) 2023 Julien Veyssier <julien-nc@posteo.net>
  -
  - @author 2023 Julien Veyssier <julien-nc@posteo.net>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -->

<template>
	<div class="pexels-photo-reference">
		<div class="photo-wrapper">
			<strong>
				{{ richObject.alt }}
			</strong>
			<span>
				{{ richObject.photographer }}
			</span>
			<div v-if="!isLoaded" class="loading-icon">
				<NcLoadingIcon
					:size="44"
					:title="t('tutorial_4', 'Loading gif')" />
			</div>
			<img v-show="isLoaded"
				class="image"
				:src="richObject.proxied_url"
				@load="isLoaded = true">
			<a v-show="isLoaded"
				class="attribution"
				target="_blank"
				:title="poweredByTitle"
				href="https://pexels.com">
				<div class="content" />
			</a>
		</div>
	</div>
</template>

<script>
import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'

import { imagePath } from '@nextcloud/router'

export default {
	name: 'PhotoReferenceWidget',

	components: {
		NcLoadingIcon,
	},

	props: {
		richObjectType: {
			type: String,
			default: '',
		},
		richObject: {
			type: Object,
			default: null,
		},
		accessible: {
			type: Boolean,
			default: true,
		},
	},

	data() {
		return {
			isLoaded: false,
			poweredByImgSrc: imagePath('tutorial_4', 'pexels.logo.png'),
			poweredByTitle: t('tutorial_4', 'Powered by Pexels'),
		}
	},

	computed: {
	},

	methods: {
	},
}
</script>

<style scoped lang="scss">
.pexels-photo-reference {
	width: 100%;
	padding: 12px;

	.photo-wrapper {
		width: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		position: relative;

		.image {
			max-height: 300px;
			max-width: 100%;
			border-radius: var(--border-radius);
			margin-top: 8px;
		}

		.attribution {
			position: absolute;
			left: 0;
			bottom: 0;
			height: 33px;
			width: 80px;
			padding: 0;
			border-radius: var(--border-radius);
			background-color: var(--color-main-background);
			.content {
				height: 33px;
				width: 80px;
				background-image: url('../../img/pexels.logo.png');
				background-size: 80px 33px;
				filter: var(--background-invert-if-dark);
			}
		}
	}
}
</style>
