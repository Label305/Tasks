# Tasks

## Installation

`composer require label305/tasks`

## Usage
To dispatch tasks use the DispatchesTasks or DispatchesContinuousTasks trait.
### Example
```
public function ping() {
    $this->dispatchTask((new PingJob()));
}
```



