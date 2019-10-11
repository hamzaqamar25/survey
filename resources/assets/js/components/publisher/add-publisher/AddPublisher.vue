<template>
	<div>
		<h3 class="mb-4">Add Supplier</h3>
		<div class="card">
			<div class="card-body">
				<add-publisher-form @updateSuccess="updateSuccess"></add-publisher-form>
			</div>
		</div>
	</div>
</template>

<script>
	import AddPublisherForm from './AddPublisherForm.vue'

	export default {
		components: {
			'add-publisher-form': AddPublisherForm
		},
		methods: {
			updateSuccess() {
				this.$router.push({name: 'publisher'});
			}
		}
	}
</script>
