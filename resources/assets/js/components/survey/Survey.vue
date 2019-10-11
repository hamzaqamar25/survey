<template>
	<div>
		<h3 class="mb-4">Manage Survey</h3>
		<router-link :to="{name: 'survey.addSurvey'}" class="nav-link "  activeClass="active" exact>
						Add Survey
					</router-link>
		<div class="card">
			<table class="table">
			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Name</th>
			      <th scope="col">Client</th>
			      <th scope="col">Links</th>
			      <th scope="col">Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for='survey in surveys'>
			      <th scope="row">{{survey.id}}</th>
			      <td>{{survey.survey_name}}</td>
			      <td>{{survey.client.name}}</td>
			      <td>
			      		{{survey.link}}
			      		<br/>
			      		<b>End Pages</b>
			      		<br/>
			      		{{host}}/survey/track/{{survey.survey_uuid}}-SC
			      		<br/>
			      		{{host}}/survey/track/{{survey.survey_uuid}}-QF
			      		<br/>
			      		{{host}}/survey/track/{{survey.survey_uuid}}-TM
						<br/>
						<p v-if='survey.publishers.length'>
			      		<b>Supplier link</b>
						<p v-for='pub in survey.publishers'><b>{{pub.publisher.name}}</b><br>{{host}}/survey/intermediate/{{survey.survey_uuid}}?pid={{pub.publisher.p_uuid}}<span v-if="pub.publisher.supcode">&{{pub.publisher.supcode}}=[Supplier Unique Code]</span>

						 </p>
						</p>
			      </td>
			      <td>
						<a :href="'/survey/edit-survey/'+survey.id" class="nav-link" >Edit</a>
			      </td>
			    </tr>
			  </tbody>
			</table>

		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../config";

	export default {
		data() {
			return {
				surveys : [],
				host:''
			};
		},
		computed: mapState({
			user: state => state.auth
		}),
		mounted() {
			this.getAllSurvey();
			var protocol = location.protocol;
			var slashes = protocol.concat("//");
			this.host = slashes.concat(window.location.hostname);
			
		},
		methods: {
			getAllSurvey() {
				axios.get(api.getAllSurvey)
					.then((res) => {
						this.surveys = res.data.survey;
					})
			}
		}
	}
</script>
