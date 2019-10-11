<template>
	<div class="container">
		<div class="jumbotron">
			<img src="/rmr-red.jpg" width="200" height="150" border="0" alt="">
		</div>
	</div>
</template>

<script>
	import {siteName} from './../../config';

	export default {
		data() {
			return {
				siteName: siteName
			}
		}
	}
</script>
