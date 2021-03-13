<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">AUDIO CONVERTER</a>
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <div class="nav-link">
            <router-link to="/">
              Home
            </router-link>
          </div>
          <!-- <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a> -->
        </li>
        <li class="nav-item">
          <div class="nav-link">
            <button class="btn btn-sm btn-secondary rounded-0" @click="handleLogout">
              Sign Out
            </button>
          </div>
        </li>
      </ul>
    </nav>
    <br />
    <div class="container jumbotron bg-dark text-white" style="padding: 1rem">
      <h4 class="text-center">Convert WAV to MP3</h4>
      <form @submit.prevent="handleConversion" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-5 col-sm-4 col-xs-12">
            <div class="form-group row">
              <label for="audioFile" class="col-sm-3">WAV file</label>
              <div class="col-sm-9">
                <input
                type="file"
                name="audioFile"
                id="audioFile"
                class="form-control-file"
                v-on:change="handleUpload" />
              </div>
            </div>
          </div>
          <div class="col-md-5 col-sm-4 col-xs-12">
            <div class="form-group row">
              <label for="audioFile" class="col-sm-3">Output</label>
              <div class="col-sm-9">
                <input
                  type="text"
                  name="fileName"
                  id="fileName"
                  class="form-control form-control-sm rounded-0"
                  placeholder="Name of the converted file with extension"
                  v-model="form.fileName"
                >
              </div>
            </div>
          </div>
          <div class="col-md-2 col-sm-4 col-xs-12">
            <input
              type="submit"
              value="Convert file"
              class="btn btn-sm btn-block btn-primary rounded-0"
            >
          </div>
        </div>
      </form>
    </div>
    <br />
    <div class="container">
      <h4 class="text-center">Conversion history</h4>
      <div v-if="isFormLoading">
        <br />
        <br />
        <div className="d-flex justify-content-center">
          <div className="spinner-border text-dark" role="status">
            <span className="sr-only">Loading...</span>
          </div>
        </div>
      </div>
      <table v-if="!isFormLoading" class="table table-sm table-responseive table-stripped">
        <thead class="thead text-center">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Converted file</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, index) in dataRows" :key="row.id">
            <th scope="row" class="text-center">{{index + 1}}</th>
            <td class="text-center">{{row.created_at}}</td>
            <td>{{row.file}}</td>
            <td class="text-center">
              <button
                class="btn btn-sm btn-info rounded-0"
              >
                Download file
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import CloudConvert from 'cloudconvert';
import api from '../services/api';

export default {
  data() {
    return {
      form: {
        fileName: '',
      },

      fileTempPath: '',

      formData: new FormData(),

      dataRows: [],

      isFormLoading: false,
    }
  },

  methods: {
    async fetchData() {
      this.isFormLoading = true;

      await api.get('/api/audio-files').then(response => {
        console.log(response);
        this.dataRows = response.data.files;
      })
      .catch(error => {
        if (error.response.status === 401) {
          this.$router.push('/login');
        } else {
          window.alert(error.response);
        }
      });

      this.isFormLoading = false;
    },

    // handleValueChange(e) {
    //   const eventTarget = e.target;
    //   const targetType = eventTarget.type;
    //   const targetName = eventTarget.name;
    //   const targetValue = targetType === 'file' ? eventTarget.files[0] : eventTarget.value;

    //   if (targetType === 'file') {
    //     this.formData.append(targetName, targetValue, targetValue.name);
    //   } else {
    //     this.formData.set(targetName, targetValue);
    //   }
    // },

    handleUpload(e) {
      const eventTarget = e.target;
      const targetName = eventTarget.name;
      const targetValue = eventTarget.files[0];

      console.log(eventTarget);

      this.formData.append(targetName, targetValue, targetValue.name);

      this.fileTempPath = URL.createObjectURL(targetValue);
    },

    handleConversion() {
      Promise.all(
        this.buildPayload()
      ).then(() => {
        console.log(this.formData.get('audioFile'));
        console.log(this.formData.get('fileName'));
        this.convertJob();
      });
    },

    buildPayload() {
      for (const [key, value] of Object.entries(this.form)) {
        this.formData.set(key, value);
      }

      return this.formData;
    },

    async convertJob() {
      const authToken = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNWZmMjY4ZmMxOWZjOWZmM2NjMjEyMmYzNjk2ZDQxNzk0ZDMyZGZjNzI0OGE2ZmQwZjdiMWZkZmUwMTE5ZjRkNDA2ZGE4MWVlN2Q4ZjM0Y2UiLCJpYXQiOiIxNjE1NTcyNzI4LjY0NDAyMSIsIm5iZiI6IjE2MTU1NzI3MjguNjQ0MDI1IiwiZXhwIjoiNDc3MTI0NjMyOC42MDU3NTQiLCJzdWIiOiI0OTU4NTUwOCIsInNjb3BlcyI6WyJ1c2VyLnJlYWQiLCJ1c2VyLndyaXRlIiwidGFzay5yZWFkIiwidGFzay53cml0ZSIsIndlYmhvb2sucmVhZCIsIndlYmhvb2sud3JpdGUiLCJwcmVzZXQucmVhZCIsInByZXNldC53cml0ZSJdfQ.XpFWfh0VOlSL5VFvkfmNn1Z-npl54uSqg2bMSyiwTNGmR0ys6qJwN42R-nn6qEnLCxrbblzkXCLLxmx5Nw4vaV77sMMUNs2BwbjJwXw-ypZx-qQFmjNha36bdK0HKuJBO_hu37aRW5buG18pFLb2eCFzpNDrpOX13reb7zH7yzDjgNGypkTF5M0jI-5Se9gPJHRBkmMqfUHWsh2gOD1jUd9V0r1cd-dV_F-o8ZXoE6DWfaY8uLV0THhRiijkeSRjCJcVc5NtLJPHmY2K_Tu8xtF6jH6Z9-EJqLEqNYwDE8mmovCE55RgabCSApgdO9pmLH1eA2IFhglLSueZFttVwnikuxh-hAwW-ZpnYW6WKv-TPGQKQW7ENsuWn_xXo1cmlAUQ0tTZgGKqXLEuiAsTPBURQVzLkbEfibuTouPzC8ASGE9_6WovfCPEI9kVS0anPMcB7J0ZsJl-hO3er824NmRUK2B6cw2Lmvta-SyCxRWlmSvxqJY2zcNGF6OcG9SC9jnJ1pXRLFnperNn8mU8gAg39us09W6IHUj_qD6lQKpbc4AcPvUr_QinvIO1_LuZe-Qf-cxGLDzeME_e1_XgQbq--R_u2rfOM5Sb41KZyYPpFQjkUJJgw7f-Ygit7PAcQurcSW9BjxlETCasd8VdJHehlP0Ps8vPysD0CdR6tSg";

      console.log(this.fileTempPath);

      await axios.post('https://api.sandbox.cloudconvert.com/v2/jobs', 
        {
          "tasks": {
              "file_upload": {
                  "operation": "import/upload",
              },
              "convert": {
                  "operation": "convert",
                  "input_format": "wav",
                  "output_format": "mp3",
                  "engine": "ffmpeg",
                  "input": [
                      "file_upload"
                  ],
                  "audio_codec": "mp3",
                  "audio_qscale": 0,
                  "engine_version": "4.1.4",
                  "filename": this.form.fileName
              },
              "file_download": {
                  "operation": "export/url",
                  "input": [
                      "convert"
                  ],
                  "inline": false,
                  "archive_multiple_files": false
              }
          }
        },
        {
          headers: {
            Authorization: authToken
          }
        }
      ).then (async response => {
        console.log(response);

          await axios.post('https://api.sandbox.cloudconvert.com/v2/import/upload', 
            this.formData.get('audioFile'),
            {
              headers: {
                Authorization: authToken,
                "Content-type": "application/json"
              }
            }
          )
      });
    },

    handleLogout() {
      api.post('logout').then(() => {
        this.$router.push('/login');
      })
    }
  },

  mounted() {
    this.fetchData();
  }
}
</script>

<style lang="css" scoped>

</style>