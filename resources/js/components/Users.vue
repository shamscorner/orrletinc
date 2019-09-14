<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Our Employees</h3>

            <div class="card-tools">
              <button class="btn btn-sm btn-success" @click="newModal">
                Add new
                <i class="fas fa-user-plus fa-fw"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Position</th>
                  <th>Registered at</th>
                  <th>Modify</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | capitalize }}</td>
                  <td>{{ user.created_at | showDate }}</td>
                  <td>
                    <a href="#" class="btn-sm btn-link text-primary" @click="editModal(user)">
                      <i class="fa fa-edit fa-fw"></i>
                      Edit
                    </a>
                    |
                    <a
                      href="#"
                      class="btn-sm btn-link text-danger"
                      @click="deleteUser(user.id)"
                    >
                      <i class="fa fa-trash fa-fw"></i>
                      Remove
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="addNewEmployee"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewEmployee"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5
              class="modal-title"
              id="addNewEmployee"
            >{{ editmode ? "Edit Employee's Data" : "Add New Employee" }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editmode ? updateUser() : createUser()"
            @keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                  placeholder="Name"
                />
                <has-error :form="form" field="name"></has-error>
              </div>

              <div class="form-group">
                <input
                  v-model="form.email"
                  type="text"
                  name="email"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                  placeholder="Email address"
                />
                <has-error :form="form" field="email"></has-error>
              </div>

              <div class="form-group">
                <textarea
                  v-model="form.bio"
                  name="bio"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('bio') }"
                  placeholder="Short bio for employee (Optional)"
                ></textarea>
                <has-error :form="form" field="bio"></has-error>
              </div>

              <div class="form-group">
                <select name="type" v-model="form.type" id="type" class="form-control">
                  <option value>Select Role</option>
                  <option value="admin">Admin</option>
                  <option value="standard">Standard</option>
                  <option value="manager">Manager</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>

              <div class="form-group">
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                  placeholder="Password"
                />
                <has-error :form="form" field="password"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button v-show="editmode" type="submit" class="btn btn-warning">Update</button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editmode: true,
      users: {},
      form: new Form({
        id: "",
        name: "",
        email: "",
        bio: "",
        type: "",
        password: "",
        photo: ""
      })
    };
  },
  methods: {
    updateUser() {
      // start the progress bar
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          // success
          // custom event listener
          Fire.$emit("after-create");
          // hide the modal window
          $("#addNewEmployee").modal("hide");

          swal.fire("Updated!", "Information successfully updated.", "success");
          // stop the progress bar
          this.$Progress.finish();
        })
        .catch(() => {
          // error
          this.$Progress.fail();
        });
    },
    editModal(user) {
      this.editmode = true;
      this.form.reset();
      $("#addNewEmployee").modal("show");
      this.form.fill(user);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNewEmployee").modal("show");
    },
    loadUsers() {
      axios.get("api/user").then(({ data }) => (this.users = data.data));
    },
    createUser() {
      // start the progress bar
      this.$Progress.start();
      // send the request
      this.form
        .post("api/user")
        .then(() => {
          // custom event listener
          Fire.$emit("after-create");
          // hide the modal window
          $("#addNewEmployee").modal("hide");
          // make a toast notification
          toast.fire({
            type: "success",
            title: "Registered Successfully!"
          });
          // stop the progress bar
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    deleteUser(id) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        })
        .then(result => {
          if (result.value) {
            this.form
              .delete("api/user/" + id)
              .then(() => {
                Fire.$emit("after-create");
                swal.fire(
                  "Deleted!",
                  "Employee's data has been deleted.",
                  "success"
                );
              })
              .catch(() => {
                swal.fire("Failed!", "Something went wrong.", "warning");
              });
          }
        });
    }
  },
  created() {
    this.loadUsers();
    // load the users every 3 seconds
    // setInterval(() => this.loadUsers(), 3000);
    // load the users with custom event listener
    Fire.$on("after-create", () => {
      this.loadUsers();
    });
  }
};
</script>
