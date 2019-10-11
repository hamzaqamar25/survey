<template>
	<div>
		<h3 class="mb-4">Edit Survey</h3>
		<div class="card">
			<div class="card-body">
				<edit-survey-form @updateSuccess="updateSuccess"></edit-survey-form>
			</div>
		</div>
	</div>
</template>

<script>
	import EditSurveyForm from './EditSurveyForm.vue'

	export default {
		components: {
			'edit-survey-form': EditSurveyForm
		},
		methods: {
			updateSuccess() {
				this.$router.push({name: 'survey'});
			}
		}
	}
</script>
