<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12 mt-3">
        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-info">
            <h3 class="widget-user-username">Alexander Pierce</h3>
            <h5 class="widget-user-desc">Founder &amp; CEO</h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle elevation-2" :src="getProfilePhoto()" alt="User Avatar" />
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">3,200</h5>
                  <span class="description-text">SALES</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">13,000</h5>
                  <span class="description-text">FOLLOWERS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4">
                <div class="description-block">
                  <h5 class="description-header">35</h5>
                  <span class="description-text">PRODUCTS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link" href="#activity" data-toggle="tab">Activity</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
              </li>
            </ul>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane" id="activity">
                <h1>Display User Activity</h1>
              </div>

              <div class="tab-pane active" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Name</label>

                    <input
                      v-model="form.name"
                      type="email"
                      class="form-control"
                      id="inputName"
                      placeholder="Name"
                      :class="{ 'is-invalid': form.errors.has('name') }"
                    />
                    <has-error :form="form" field="name"></has-error>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="control-label">Email</label>

                    <input
                      v-model="form.email"
                      type="email"
                      class="form-control"
                      id="inputEmail"
                      placeholder="Email"
                      :class="{ 'is-invalid': form.errors.has('email') }"
                    />
                    <has-error :form="form" field="email"></has-error>
                  </div>
                  <div class="form-group">
                    <label for="inputBio" class="control-label">Short Bio</label>

                    <textarea
                      v-model="form.bio"
                      class="form-control"
                      id="inputBio"
                      placeholder="Add something about you..."
                      :class="{ 'is-invalid': form.errors.has('bio') }"
                    ></textarea>
                    <has-error :form="form" field="bio"></has-error>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="control-label">Experience</label>

                    <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                  </div>
                  <div class="form-group" v-if="$gate.isAdmin()">
                    <label for="type" class="control-label">Position</label>

                    <select name="type" v-model="form.type" id="type" class="form-control">
                      <option value>Select Role</option>
                      <option value="admin">Admin</option>
                      <option value="standard">Standard</option>
                      <option value="manager">Manager</option>
                    </select>
                    <has-error :form="form" field="type"></has-error>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="control-label">Skills</label>

                    <input type="text" class="form-control" id="inputSkills" placeholder="Skills" />
                  </div>
                  <div class="form-group">
                    <label for="inputProfilePicture" class="control-label">Profile Picture</label>

                    <input
                      type="file"
                      name="photo"
                      class="form-control-file"
                      id="inputProfilePicture"
                      @change="updateProfile"
                    />
                  </div>
                  <div class="form-group">
                    <label for="inputPassword" class="control-label">Password</label>

                    <input
                      v-model="form.password"
                      type="password"
                      name="password"
                      id="inputPassword"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('password') }"
                      placeholder="Password"
                    />
                    <has-error :form="form" field="password"></has-error>
                  </div>
                  <div class="form-group mt-4">
                    <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Update</button>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
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
  mounted() {
    console.log("Profile Component mounted.");
  },
  methods: {
    getProfilePhoto() {
      //default avatar pic if there is no photo of user
      let photo = "img/user.png";
      //returns the current path of the
      if (this.form.photo) {
        // indexOf returns -1 if not matches
        if (this.form.photo.indexOf("base64") != -1) {
          photo = this.form.photo;
        } else {
          photo = "img/profile/" + this.form.photo;
        }
      }
      return photo;
    },
    updateInfo() {
      // start the progress bar
      this.$Progress.start();
      this.form
        .put("api/profile")
        .then(() => {
          // stop the progress bar
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    updateProfile(element) {
      let file = element.target.files[0];
      //console.log(file);
      let reader = new FileReader();

      if (file["size"] < 2111775) {
        reader.onloadend = () => {
          //console.log("RESULT", reader.result);
          // do something after the file uploaded
          this.form.photo = reader.result;
        };
      } else {
        swal.fire("Oops...", "File size is more than 2MB", "error");
      }

      reader.readAsDataURL(file);
    }
  },
  created() {
    axios.get("api/profile").then(({ data }) => {
      this.form.fill(data);
    });
  }
};
</script>
