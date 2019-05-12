<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-md-2">

                <button class="btn btn-primary btn-block">Add New Task</button>
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
            uri:'http://laravel-crud-vue.local/tasks/'
          }
        },
        methods:{
          loadTasks(){
            axios.get(this.uri).then(response => {
              this.tasks = response.data.tasks //assign response from backend to an empty array tasks: []
            });
          }
        },
        mounted() {
            this.loadTasks();
        }
    }
</script>
