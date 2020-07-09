#Tasks

##Installation
Add the repository to your composer.json
```  
  "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:Label305/Tasks.git"
        }
    ],
```

`composer require label305/tasks`

Follow the steps in composer to get a deploy key.

##Usage
To dispatch tasks use the DispatchesTasks or DispatchesContinuousTasks trait.
###Example
```
public function ping() {
    $this->dispatchTask((new PingJob()));
}
```



