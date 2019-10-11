<template>
	<div>
		<h3 class="mb-4">Edit Supplier</h3>
		<div class="card">
			<div class="card-body">
				<edit-publisher-form @updateSuccess="updateSuccess"></edit-publisher-form>
			</div>
		</div>
	</div>
</template>

<script>
	import EditPublisherForm from './EditPublisherForm.vue'

	export default {
		components: {
			'edit-publisher-form': EditPublisherForm
		},
		methods: {
			updateSuccess() {
				this.$router.push({name: 'publisher'});
			}
		}
	}
</script>
