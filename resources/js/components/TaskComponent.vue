<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-md-2">

                <button class="btn btn-primary btn-block" @click="createModal">Add New Task</button>
                <br>
                <table class="table table-striped table-dark" v-if="tasks">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Name</th>
                      <th scope="col">Body</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(task, index) in tasks">
                      <th scope="row">{{ index + 1 }}</th>
                      <td>{{ task.name }}</td>
                      <td>{{ task.body }}</td>
                      <td><button class="btn btn-info btn-sm">Edit</button></td>
                      <td><button class="btn btn-danger btn-sm">Delete</button></td>
                    </tr>
                  </tbody>
                </table>

              <!-- Create Modal -->
              <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Create Modal</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                          <li v-for="error in errors">{{ error }}</li>
                        </ul>
                      </div>
                      <div class="form-group">
                        <label for="name">Name</label>
                        <input v-model="task.name" type="text" name="" id="name" class="form-control">
                        <label for="description">Description</label>
                        <input v-model="task.body" type="text" name="" id="description" class="form-control">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" @click="createTask">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>    
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return{
            task:{
              name: '',
              body: ''
            },
            tasks: [],
            uri:'http://laravel-crud-vue.local/tasks',
            errors: []
          }
        },
        methods:{
          loadTasks(){
            axios.get(this.uri).then(response => {
              this.tasks = response.data.tasks //assign response from the backend to an empty array tasks: []
            });
          },
          createTask(){
            axios.post(this.uri, {name: this.task.name, body: this.task.body}).then(response => {
              this.tasks.push(response.data.task);
              $("#create-modal").modal("hide");
            }).catch(error => {
              this.errors = [];
              if (error.response.data.errors.name) {
                this.errors.push(error.response.data.errors.name[0]);
              }
              if (error.response.data.errors.body) {
                this.errors.push(error.response.data.errors.body[0]);
              }
            });
          },
          createModal(){
            $("#create-modal").modal("show");
          }
        },
        mounted() {
            this.loadTasks();
        }
    }
</script>
