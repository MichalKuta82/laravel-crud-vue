<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-md-2">
              <div v-if="!loading">
                <img :src="image" class="rounded mx-auto d-block" alt="loader">
              </div>

            <div v-else>
                <button class="btn btn-primary btn-block" @click="loadCreateModal">Add New Task</button>
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
                      <td><button class="btn btn-info btn-sm" @click="loadUpdateModal(index)">Edit</button></td>
                      <td><button class="btn btn-danger btn-sm" @click="deleteTask(index)">Delete</button></td>
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

              <!-- Update Modal -->
              <div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Update Modal</h5>
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
                        <input v-model="new_update_task.name" type="text" name="" id="name" class="form-control">
                        <label for="description">Description</label>
                        <input v-model="new_update_task.body" type="text" name="" id="description" class="form-control">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" @click="updateTask">Save changes</button>
                    </div>
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
            errors: [],
            new_update_task: [],
            image: 'images/loader1.gif',
            loading: false,
            toastr: toastr.options = {"positionClass": "toast-top-full-width"}
          }
        },
        methods:{
          loadTasks(){
            axios.get(this.uri).then(response => {
              this.tasks = response.data.tasks; //assign response from the backend to an empty array tasks: []
              this.loading = true;
            });
          },
          loadCreateModal(){
            $("#create-modal").modal("show");
          },
          loadUpdateModal(index){
            this.errors = [];
            $("#update-modal").modal("show");
            this.new_update_task = this.tasks[index];
          },
          createTask(){
            axios.post(this.uri, {name: this.task.name, body: this.task.body}).then(response => {
              this.tasks.push(response.data.task);
              this.resetData();
              $("#create-modal").modal("hide");
              toastr.success(response.data.message);
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
          updateTask(){
            axios.patch(this.uri + "/" + this.new_update_task.id, {
              name: this.new_update_task.name,
              body: this.new_update_task.body
            }).then(response => {
              $("#update-modal").modal("hide");
              toastr.success(response.data.message);
            }).catch(error => {
              if (error.response.data.errors.name) {
                this.errors.push(error.response.data.errors.name[0]);
              }
              if (error.response.data.errors.body) {
                this.errors.push(error.response.data.errors.body[0]);
              }
            });
          },
          deleteTask(index){
            let confirmBox = confirm("Do you really want to delete this?");
            if (confirmBox == true) {
              axios.delete(this.uri + "/" + this.tasks[index].id)
                .then(response => {
                  this.$delete(this.tasks, index); // Vue.delete(this.task, index)
                  toastr.success(response.data.message);
                }).catch(error => {
                  console.log("could not delete");
                });
            }
          },
          resetData(){
            this.task.name = '';
            this.task.body = '';
          }
        },
        mounted() {
            this.loadTasks();
        }
    }
</script>
