<template>
	<div>
		<h3 class="mb-4">Survey Report</h3>
		<div class="row">
			<div class="form-group col-md-4">
				<label for="survey">Survey</label>
				<select class="form-control" id="survey" v-model="form.survey" :disabled="loading" placeholder='Select survey' @change='changeSurvey'>
			        <option value="0">
			            Select Survey
			        </option>
			        <option v-for="item in surveys" :value="item.value">
			            {{item.text}}
			        </option>
			    </select>
			</div>

			<div class="form-group col-md-4" v-if='publishers.length > 0'>
				<label for="survey">Publishers</label>
				<select class="form-control" id="publisher" v-model="form.publisher" :disabled="loading">
			        <option value="0">
			            Select Publisher
			        </option>
			        <option v-for="item in publishers" :value="item.value">
			            {{item.text}}
			        </option>
			    </select>
			</div>
			<div class="form-group col-md-4">
				<button type="submit" class="btn btn-primary" :disabled="loading" style="margin-top: 30px;" @click='getReport'>
					<span v-show="loading">Getting Report</span>
					<span v-show="!loading">Get Report</span>
				</button>
			</div>

		</div>
		<div class="card" v-if='surveysdata.length > 0'>
			<table class="table">
			  <thead>
			    <tr>
			      <th scope="col">Date</th>
			      <th scope="col">Click</th>
			      <th scope="col">Success</th>
			      <th scope="col">Quota Full</th>
			      <th scope="col">Terminate</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for='survey in surveysdata'>
			      <th scope="row">{{survey.action_date}}</th>
			      <td>{{survey.clicks}}</td>
			      <td>
			      		<a v-if='form.publisher != ""' :href="'/survey/report-survey/'+form.survey+'/'+survey.action_date+'/'+form.publisher+'/success'" target="_blank" class="nav-link " >{{survey.ss}}</a>
			      		<a v-else :href="'/survey/report-survey/'+form.survey+'/'+survey.action_date+'/success'" target="_blank" class="nav-link " >{{survey.ss}}</a>
			      </td>
			      <td>{{survey.qf}}</td>
			      <td>{{survey.tm}}</td>
			    </tr>
			  </tbody>
			</table>

		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../../config";
	export default {
		data() {
			return {
				allsurveys : [],
				surveys : [],
				loading: false,
				surveysdata : [],
		        publishers: [],
				form: {
					survey: 0,
					publisher:''
				},
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
			getReport() {
				this.surveysdata = [];
				this.loading = true;
				axios.post(api.getSurveyReport, this.form)
					.then((res) => {
						this.loading = false;
						this.surveysdata = res.data.items;
					});
			},
			changeSurvey() {
				this.getSurveyPublisher()
			},
			getAllSurvey() {
				axios.get(api.getAllSurvey)
					.then((res) => {
						let surveys = res.data.survey;
						this.allsurveys = surveys;
						surveys.forEach(c => {
							this.surveys.push({text: c.survey_name, value: c.survey_uuid });
						})
					})
			},
			getSurveyPublisher() {
				let sid = 0;
				this.allsurveys.forEach(s => {
					if(s.survey_uuid == this.form.survey) {
						sid = s.id;
					}
				});
				if(sid != 0) {
				axios.get(api.getSurveyPublisher+'/'+sid)
					.then((res) => {
						let data = res.data;
						if(data.publishers) {
							this.publishers=[];

							data.publishers.forEach((pub) => {
								let arr = {
											'value' : pub.publisher.p_uuid,
											'text' : pub.publisher.name,
											}
								this.publishers.push(arr);
							});

							if(this.publishers.length > 1) {
								let arr = {
											'value' : '',
											'text' : 'All',
										}
								this.publishers.unshift(arr);
							}
							this.form.publisher = 0;
							
						}			
					});
				}
			},
		}
	}
</script>
