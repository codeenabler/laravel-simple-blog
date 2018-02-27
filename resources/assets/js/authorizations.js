let user = window.App.user;

module.exports = {
    owns(model, prop = 'user_id') {
        if(model) return model[prop] === user.id;
    }
};
