export interface file {
  name: string;
}

export interface flash {
  message: string;
  type: string;
}

export interface user {
  id: number;
  name: string;
  email: string;
  avatar: string;
  email_verified_at: string;
  created_at: string;
  updated_at: string;
  phone: string;
  password: string;
  role: string;
  can_action?: boolean;
  active: boolean;
  department: department;
  department_id: number;
}

export interface tag {
  slug: string;
  color: string;
}

export interface attachment {
  id: number;
  slug: string;
  url: string;
}

export interface task {
  id: number;
  title: string;
  description: string;
  due_date: string;
  priority: number;
  status: string;
  assignee: user;
  assigner: user;
  created_by: user;
  created_at: string;
  updated_at: string;
  started_at: string;
  result: string;
  attachments: attachment[];
  sub_tasks: subTask[];
  tags: tag[];
  followers: user[];
}

export interface subTask {
  id: number;
  title: string;
  description: string;
  due_date: string;
  priority: number;
  status: string;
  assignee: user;
  assigner: user;
  created_by: user;
  created_at: string;
  updated_at: string;
  started_at: string;
  result: string;
  attachments: attachment[];
  sub_tasks: subTask[];
  tags: tag[];
  followers: user[];
}

export interface task_list {
  id: number;
  name: string;
  tasks: task[];
  project: project;
}

export interface project {
  id: number;
  title: string;
  description: string;
  created_at: string;
  updated_at: string;
  created_by: user;
  type: string;
  task_lists: task_list[];
  attachments: attachment[];
}

export interface task_message {
  id?: number;
  content: string;
  sent_by: user;
}

export interface task_result {
  task: task;
  description: string;
}

export interface department {
  id: number;
  name: string;
  employees?: user[];
  created_at: string;
  updated_at: string;
  can_delete: boolean;
}

export interface timesheet {
  id: number;
  date: string;
  check_in_time: string;
  check_out_time: string;
}
